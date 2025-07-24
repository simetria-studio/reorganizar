<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\School;
use App\Models\Student;
use App\Models\Certificate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['student', 'school']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('student_name', 'like', "%{$search}%")
                  ->orWhere('verification_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('certificate_type')) {
            $query->byCertificateType($request->certificate_type);
        }

        if ($request->filled('course_level')) {
            $query->byCourseLevel($request->course_level);
        }

        if ($request->filled('completion_year')) {
            $query->byYear($request->completion_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(10);
        $schools = School::active()->orderBy('name')->get();

        return view('certificates.index', compact('certificates', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schools = School::active()->orderBy('name')->get();
        $students = collect();

        if ($request->filled('student_id')) {
            $student = Student::with('school')->find($request->student_id);
            if ($student) {
                $students = collect([$student]);
            }
        }

        return view('certificates.create', compact('schools', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'certificate_type' => 'required|in:conclusao,historico,declaracao',
            'course_level' => 'required|in:ensino_medio,ensino_fundamental,educacao_infantil,ensino_tecnico,ensino_superior',
            'course_name' => 'nullable|string|max:255',
            'completion_year' => 'required|integer|min:1990|max:2030',
            'completion_date' => 'required|date',
            'director_name' => 'required|string|max:255',
            'director_title' => 'nullable|string|max:100',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_title' => 'nullable|string|max:100',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'required|string|max:255',
            'student_birth_place' => 'required|string|max:255',
            'school_authorization' => 'nullable|string|max:255',
            'school_inep' => 'nullable|string|max:20',
            'observations' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::with('school')->findOrFail($validated['student_id']);
            $school = $student->school;

            // Gerar dados automáticos
            $certificate = new Certificate();
            $certificateNumber = $certificate->generateCertificateNumber();
            $verificationCode = $certificate->generateVerificationCode();

            // Preencher dados do certificado
            $certificateData = array_merge($validated, [
                'school_id' => $school->id,
                'certificate_number' => $certificateNumber,
                'issue_date' => now(),
                'verification_code' => $verificationCode,

                // Dados da escola
                'school_name' => $school->name,
                'school_cnpj' => $school->cnpj,
                'school_address' => $school->full_address,

                // Dados do aluno
                'student_name' => $student->name,
                'student_cpf' => $student->cpf,
                'student_birth_date' => $student->birth_date,
                'student_nationality' => 'BRASILEIRA',

                // Dados de emissão
                'issue_city' => $school->city,
                'issue_state' => $school->state,

                // Valores padrão
                'director_title' => $validated['director_title'] ?? 'DIRETOR(A)',
                'secretary_title' => $validated['secretary_title'] ?? 'SECRETÁRIO(A)',
            ]);

            $certificate = Certificate::create($certificateData);

            // Gerar hash após criação
            $certificate->hash = $certificate->generateHash();
            $certificate->save();

            // Gerar PDF
            $this->generatePDF($certificate);

            // Registrar atividade
            ActivityLog::log(
                'create',
                'Certificate',
                "Certificado '{$certificate->certificate_number}' gerado para {$certificate->student_name}",
                $certificate->id
            );

            DB::commit();

            return redirect()->route('certificates.show', $certificate)
                ->with('success', 'Certificado gerado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao gerar certificado: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        $schools = School::active()->orderBy('name')->get();
        $certificate->load(['student', 'school']);
        return view('certificates.edit', compact('certificate', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'director_name' => 'required|string|max:255',
            'director_title' => 'nullable|string|max:100',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_title' => 'nullable|string|max:100',
            'observations' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $certificate->update($validated);

            // Regenerar PDF se necessário
            $this->generatePDF($certificate);

            DB::commit();

            return redirect()->route('certificates.show', $certificate)
                ->with('success', 'Certificado atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar certificado: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        try {
            DB::beginTransaction();

            // Remove PDF se existir
            if ($certificate->pdf_path && Storage::disk('public')->exists($certificate->pdf_path)) {
                Storage::disk('public')->delete($certificate->pdf_path);
            }

            $certificate->delete();

            DB::commit();

            return redirect()->route('certificates.index')
                ->with('success', 'Certificado excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao excluir certificado: ' . $e->getMessage());
        }
    }

    /**
     * Preview certificate before download
     */
    public function preview(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written,
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place,
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality,
            'student_father' => $certificate->father_name,
            'student_mother' => $certificate->mother_name,
            'completion_year' => $certificate->completion_year,
            'course_level' => $certificate->course_level_label,
            'issue_location' => $certificate->issue_city,
            'issue_state' => $certificate->issue_state,
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code,
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => $certificate->school_authorization ?? '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        return view('certificates.preview', compact('certificate', 'data'));
    }

    /**
     * View PDF template directly for CSS adjustments
     */
    public function viewPdf(Certificate $certificate = null)
    {
        // Se não tiver certificado, usar dados de exemplo
        if (!$certificate) {
            $certificate = Certificate::first();
        }

        if ($certificate) {
            $certificate->load(['student', 'school']);
        }

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name ?? 'CENTRO ESTADUAL DE TEMPO INTEGRAL FRANCISCA TRINDADE',
            'school_cnpj' => $certificate->formatted_school_cnpj ?? '08.055.298/0001-49',
            'school_inep' => $certificate->school_inep ?? '22136703',
            'school_address' => $certificate->school_address ?? 'RUA DO ARAME, S/N – BAIRRO SANTINHO – CEP: 64.100-000 – BARRAS-PI',
            'school_authorization' => $certificate->school_authorization ?? '224/2022',
            'authorization_date' => '02/12/2022',
            'student_name' => $certificate->student_name ?? 'ALUNO EXEMPLO DA SILVA',
            'student_cpf' => $certificate->formatted_student_cpf ?? '000.000.000-00',
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written ?? 'JULHO',
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place ?? 'BARRAS',
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality ?? 'BRASILEIRA',
            'student_father' => $certificate->father_name ?? 'FRANCISCO DAS CHAGAS FURTADO MACHADO',
            'student_mother' => $certificate->mother_name ?? 'MARIA DA CONCEIÇÃO FERREIRA DA SILVA',
            'completion_year' => $certificate->completion_year ?? '2023',
            'course_level' => $certificate->course_level_label ?? 'MÉDIO',
            'issue_location' => $certificate->issue_city ?? 'BARRAS',
            'issue_state' => $certificate->issue_state ?? 'PI',
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code ?? 'CERT-2024-12345',
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        // Passar tanto os dados individuais quanto o objeto certificate
        $data['certificate'] = $certificate;

        // Retornar diretamente a view do PDF
        return view('certificates.pdf', $data);
    }

    /**
     * Download certificate PDF
     */
    public function downloadPDF(Certificate $certificate)
    {
        if (!$certificate->pdf_path || !Storage::disk('public')->exists($certificate->pdf_path)) {
            $this->generatePDF($certificate);
        }

        $fileName = "certificado_{$certificate->certificate_number}.pdf";

        // Registrar atividade de download
        ActivityLog::log(
            'download',
            'Certificate',
            "PDF do certificado '{$certificate->certificate_number}' foi baixado",
            $certificate->id
        );

        return response()->download(Storage::disk('public')->path($certificate->pdf_path), $fileName);
    }

    /**
     * Generate certificate PDF
     */
    public function generatePDF(Certificate $certificate)
    {
        $certificate->load(['student', 'school']);

        // Array de meses em português
        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        // Preparar dados para o template
        $data = [
            'school_name' => $certificate->school_name,
            'school_cnpj' => $certificate->formatted_school_cnpj,
            'school_inep' => $certificate->school_inep,
            'school_address' => $certificate->school_address,
            'school_authorization' => $certificate->school_authorization,
            'authorization_date' => $certificate->authorization_date ?? '02/12/2022',
            'student_name' => $certificate->student_name,
            'student_cpf' => $certificate->formatted_student_cpf,
            'student_birth_day' => $certificate->student_birth_date ? $certificate->student_birth_date->format('d') : '30',
            'student_birth_month' => $certificate->student_birth_date_written,
            'student_birth_year' => $certificate->student_birth_date ? $certificate->student_birth_date->format('Y') : '2006',
            'student_birthplace' => $certificate->student_birth_place,
            'student_birth_state' => 'PIAUÍ',
            'student_nationality' => $certificate->student_nationality,
            'student_father' => $certificate->father_name,
            'student_mother' => $certificate->mother_name,
            'completion_year' => $certificate->completion_year,
            'course_level' => $certificate->course_level_label,
            'issue_location' => $certificate->issue_city,
            'issue_state' => $certificate->issue_state,
            'issue_day' => $certificate->issue_date ? $certificate->issue_date->format('d') : '08',
            'issue_month' => $certificate->issue_date ? $months[$certificate->issue_date->format('n')] : 'FEVEREIRO',
            'issue_year' => $certificate->issue_date ? $certificate->issue_date->format('Y') : '2024',
            'verification_code' => $certificate->verification_code,
            'school_type' => 'Centro Estadual de Tempo Integral - CETI',
            'school_short_name' => 'FRANCISCA TRINDADE',
            'authorization' => $certificate->school_authorization ?? '224/2022',
            'authorization_date' => '02/12/2022'
        ];

        $dompdf = new Dompdf();

        // Passar tanto os dados individuais quanto o objeto certificate
        $data['certificate'] = $certificate;

        $html = view('certificates.pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Criar diretório se não existir
        $directory = 'certificates/' . date('Y');
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Salvar PDF
        $fileName = $directory . "/certificado_{$certificate->certificate_number}.pdf";
        Storage::disk('public')->put($fileName, $dompdf->output());

        // Atualizar caminho no banco
        $certificate->update(['pdf_path' => $fileName]);

        return $fileName;
    }

    /**
     * Verify certificate by code
     */
    public function verify(Request $request)
    {
        if ($request->method() === 'POST') {
            $request->validate([
                'verification_code' => 'required|string|max:50',
            ]);

            $certificate = Certificate::findByVerificationCode($request->verification_code);

            if (!$certificate) {
                return redirect()->back()
                    ->with('error', 'Código de verificação não encontrado.')
                    ->withInput();
            }

            return view('certificates.verify-result', compact('certificate'));
        }

        return view('certificates.verify');
    }

    /**
     * Cancel certificate
     */
    public function cancel(Certificate $certificate)
    {
        try {
            $certificate->update(['status' => 'cancelado']);

            return redirect()->back()
                ->with('success', 'Certificado cancelado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao cancelar certificado: ' . $e->getMessage());
        }
    }

    /**
     * Reissue certificate
     */
    public function reissue(Certificate $certificate)
    {
        try {
            DB::beginTransaction();

            // Marcar certificado original como reemitido
            $certificate->update(['status' => 'reemitido']);

            // Criar novo certificado
            $newCertificateData = $certificate->toArray();
            unset($newCertificateData['id'], $newCertificateData['created_at'], $newCertificateData['updated_at']);

            $newCertificate = new Certificate();
            $newCertificateData['certificate_number'] = $newCertificate->generateCertificateNumber();
            $newCertificateData['verification_code'] = $newCertificate->generateVerificationCode();
            $newCertificateData['issue_date'] = now();
            $newCertificateData['status'] = 'emitido';
            $newCertificateData['pdf_path'] = null;

            $newCertificate = Certificate::create($newCertificateData);
            $newCertificate->hash = $newCertificate->generateHash();
            $newCertificate->save();

            // Gerar PDF do novo certificado
            $this->generatePDF($newCertificate);

            DB::commit();

            return redirect()->route('certificates.show', $newCertificate)
                ->with('success', 'Certificado reemitido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erro ao reemitir certificado: ' . $e->getMessage());
        }
    }

    /**
     * Get students by school (AJAX)
     */
    public function getStudentsBySchool(Request $request, School $school)
    {
        $students = $school->students()
            ->where('status', 'formado')
            ->orderBy('name')
            ->get(['id', 'name', 'enrollment', 'grade']);

        return response()->json($students);
    }

        /**
     * Exibe o certificado em formato landscape (orientação horizontal)
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showLandscape($id)
    {
        $certificate = Certificate::findOrFail($id);

        // Carrega relacionamentos necessários
        $certificate->load(['student', 'school']);

        return view('certificates.new-pdf-landscape', compact('certificate'));
    }

        /**
     * Gera PDF do certificado em formato landscape
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function downloadLandscapePdf($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->load(['student', 'school']);

        $pdf = PDF::loadView('certificates.new-pdf-landscape', compact('certificate'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'dpi' => 150,
                      'defaultFont' => 'serif',
                      'isRemoteEnabled' => true,
                      'enable-local-file-access' => true
                  ]);

        $filename = 'certificado_' . $certificate->student->name . '_landscape.pdf';

        return $pdf->download($filename);
    }

        /**
     * Visualiza o certificado em formato landscape via stream
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function streamLandscape($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->load(['student', 'school']);

        $pdf = PDF::loadView('certificates.new-pdf-landscape', compact('certificate'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'dpi' => 150,
                      'defaultFont' => 'serif'
                  ]);

        return $pdf->stream('certificado_landscape.pdf');
    }
}
