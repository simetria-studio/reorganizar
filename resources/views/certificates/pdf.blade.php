<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado de Conclusão</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
            width: 297mm;
            height: 210mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #ffffff;
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            color: #1a1a1a;
        }

        /* Background decorativo */
        .certificate-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
            z-index: 0;
        }

        /* Borda principal ornamentada */
        .certificate-border {
            position: absolute;
            top: 6mm;
            left: 6mm;
            right: 6mm;
            bottom: 6mm;
            border: 3mm solid #004AAD;
            border-radius: 4mm;
            z-index: 1;
            background: white;
            box-shadow: inset 0 0 0 1mm #C82222;
        }

        /* Borda interna decorativa */
        .inner-border {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 0.5mm solid #004AAD;
            border-radius: 2mm;
            z-index: 2;
            opacity: 0.7;
        }

        /* Elementos decorativos nos cantos */
        .corner-decoration {
            position: absolute;
            width: 12mm;
            height: 12mm;
            z-index: 3;
        }

        .corner-decoration::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, #004AAD 0%, #0056d6 70%, transparent 70%);
            border-radius: 50%;
        }

        .corner-decoration::after {
            content: '';
            position: absolute;
            top: 2mm;
            left: 2mm;
            width: 8mm;
            height: 8mm;
            background: radial-gradient(circle, #C82222 0%, #e63946 70%, transparent 70%);
            border-radius: 50%;
        }

        .corner-tl { top: 15mm; left: 15mm; }
        .corner-tr { top: 15mm; right: 15mm; }
        .corner-bl { bottom: 15mm; left: 15mm; }
        .corner-br { bottom: 15mm; right: 15mm; }

        /* Brasão central */
        .brasao-container {
            position: absolute;
            top: 18mm;
            left: 50%;
            transform: translateX(-50%);
            z-index: 4;
        }

        .brasao {
            width: 14mm;
            height: 16mm;
            background: linear-gradient(135deg, #004AAD 0%, #0056d6 50%, #003d9a 100%);
            border-radius: 2mm;
            border: 1mm solid #ffffff;
            box-shadow: 0 2mm 4mm rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .brasao::before {
            content: '';
            position: absolute;
            top: -2mm;
            left: -2mm;
            right: -2mm;
            bottom: -2mm;
            background: linear-gradient(45deg, #C82222, transparent, #C82222);
            border-radius: 3mm;
            z-index: -1;
            opacity: 0.3;
        }

        .brasao-text {
            font-size: 1.6mm;
            font-weight: bold;
            line-height: 0.9;
            text-shadow: 0 0.5mm 1mm rgba(0,0,0,0.5);
        }

        /* Container principal do conteúdo */
        .certificate-content {
            position: absolute;
            z-index: 5;
            top: 32mm;
            left: 18mm;
            right: 18mm;
            bottom: 10mm;
            height: calc(210mm - 42mm);
            max-height: 168mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* Cabeçalho oficial */
        .header {
            text-align: center;
            margin-bottom: 3mm;
        }

        .header h1 {
            font-size: 4mm;
            font-weight: bold;
            color: #004AAD;
            margin: 0.3mm 0;
            letter-spacing: 0.3mm;
            text-transform: uppercase;
            line-height: 1;
        }

        .header .republic {
            font-size: 4.2mm;
            color: #C82222;
            text-shadow: 0 0.2mm 0.5mm rgba(0,0,0,0.1);
        }

        /* Informações da escola */
        .school-section {
            text-align: center;
            margin-bottom: 2.5mm;
            background: rgba(0, 74, 173, 0.05);
            padding: 2.5mm;
            border-radius: 2mm;
            border-left: 3mm solid #004AAD;
        }

        .school-info {
            font-size: 3mm;
            line-height: 1;
            margin-bottom: 1mm;
        }

        .school-info strong {
            color: #004AAD;
            font-size: 3.2mm;
        }

        .cnpj-inep {
            font-size: 2.8mm;
            font-weight: bold;
            color: #666;
            margin-bottom: 1mm;
        }

        .authorization {
            font-size: 2.5mm;
            color: #444;
            font-style: italic;
        }

        /* Título principal do certificado */
        .certificate-title {
            text-align: center;
            margin: 3mm 0;
            position: relative;
        }

        .certificate-title h1 {
            font-size: 5.5mm;
            font-weight: bold;
            color: #C82222;
            text-transform: uppercase;
            letter-spacing: 0.3mm;
            margin: 0;
            padding: 2.5mm;
            background: linear-gradient(135deg, rgba(0, 74, 173, 0.1) 0%, rgba(200, 34, 34, 0.1) 100%);
            border-radius: 2mm;
            border: 1.5mm solid #004AAD;
            text-shadow: 0 0.5mm 1mm rgba(0,0,0,0.1);
            line-height: 1.1;
        }

        /* Conteúdo principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }

        .content-text {
            text-align: justify;
            font-size: 3.3mm;
            line-height: 1.2;
            margin: 2mm 0;
            text-indent: 6mm;
            color: #1a1a1a;
        }

        .student-name {
            font-weight: bold;
            color: #C82222;
            text-transform: uppercase;
            font-size: 3.6mm;
            text-decoration: underline;
            text-decoration-color: #004AAD;
            text-underline-offset: 0.3mm;
        }

        .course-level {
            font-weight: bold;
            color: #004AAD;
            text-transform: uppercase;
        }

        .highlight {
            font-weight: bold;
            color: #004AAD;
        }

        /* Local e data */
        .location-date {
            text-align: center;
            font-size: 3.5mm;
            font-weight: bold;
            color: #004AAD;
            margin: 2.5mm 0;
            padding: 1.2mm;
            background: rgba(0, 74, 173, 0.05);
            border-radius: 2mm;
        }

        /* Seção de assinaturas */
        .signatures-section {
            margin-top: 3mm;
        }

        .signatures {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .signature {
            display: table-cell;
            text-align: center;
            vertical-align: top;
            width: 33.33%;
            padding: 0 2mm;
        }

        .signature-line {
            width: 25mm;
            height: 0.3mm;
            background: #004AAD;
            margin: 0 auto 1.5mm;
            position: relative;
        }

        .signature-line::before {
            content: '';
            position: absolute;
            top: -1mm;
            left: -2mm;
            right: -2mm;
            height: 3mm;
            background: transparent;
        }

        .signature-title {
            font-size: 2.8mm;
            font-weight: bold;
            color: #004AAD;
            text-transform: uppercase;
            letter-spacing: 0.1mm;
        }

        .signature-role {
            font-size: 2.2mm;
            color: #666;
            margin-top: 0.3mm;
        }

        /* Rodapé com código de verificação */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2.5mm;
            padding-top: 1.5mm;
            border-top: 0.5mm solid #004AAD;
        }

        .verification-code {
            font-size: 2.5mm;
            color: #666;
            font-family: 'Courier New', monospace;
        }

        .issue-info {
            font-size: 2.5mm;
            color: #666;
        }

        /* Selo de autenticidade */
        .authenticity-seal {
            position: absolute;
            bottom: 12mm;
            right: 12mm;
            width: 12mm;
            height: 12mm;
            background: radial-gradient(circle, #004AAD 0%, #0056d6 70%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5mm;
            font-weight: bold;
            text-align: center;
            line-height: 0.9;
            z-index: 6;
            box-shadow: 0 1mm 3mm rgba(0,0,0,0.3);
            border: 0.5mm solid white;
        }

        /* Elementos decorativos adicionais */
        .decorative-line {
            position: absolute;
            height: 0.5mm;
            background: linear-gradient(90deg, transparent 0%, #004AAD 50%, transparent 100%);
            z-index: 2;
        }

        .line-top {
            top: 35mm;
            left: 22mm;
            right: 22mm;
        }

        .line-bottom {
            bottom: 25mm;
            left: 22mm;
            right: 22mm;
        }

        /* Padrão de fundo sutil */
        .bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.03;
            background-size: 20mm 20mm;
            background-image: radial-gradient(circle, #004AAD 1px, transparent 1px);
            z-index: 1;
        }
    </style>
</head>
<body>
    @php
        // Função para converter mês numérico para nome em português
        function getMonthName($monthNumber) {
            $months = [
                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
            ];
            return $months[$monthNumber] ?? 'Janeiro';
        }

        // Função para formatar data completa em português
        function formatDatePt($date) {
            if (!$date) return '01 de Janeiro de 2000';

            try {
                // Se for objeto Carbon/DateTime
                if (is_object($date) && method_exists($date, 'format')) {
                    $day = $date->format('d');
                    $month = getMonthName((int)$date->format('n'));
                    $year = $date->format('Y');
                    return "$day de $month de $year";
                }

                // Se for string, converter para timestamp
                if (is_string($date)) {
                    $timestamp = strtotime($date);
                    if ($timestamp === false) return '01 de Janeiro de 2000';
                    $day = date('d', $timestamp);
                    $month = getMonthName((int)date('n', $timestamp));
                    $year = date('Y', $timestamp);
                    return "$day de $month de $year";
                }

                // Se for timestamp
                if (is_numeric($date)) {
                    $day = date('d', $date);
                    $month = getMonthName((int)date('n', $date));
                    $year = date('Y', $date);
                    return "$day de $month de $year";
                }

            } catch (Exception $e) {
                return '01 de Janeiro de 2000';
            }

            return '01 de Janeiro de 2000';
        }

        // Função para formatar data e hora
        function formatDateTimePt($date) {
            if (!$date) return date('d/m/Y H:i');

            try {
                // Se for objeto Carbon/DateTime
                if (is_object($date) && method_exists($date, 'format')) {
                    return $date->format('d/m/Y H:i');
                }

                // Se for string
                if (is_string($date)) {
                    $timestamp = strtotime($date);
                    return $timestamp !== false ? date('d/m/Y H:i', $timestamp) : date('d/m/Y H:i');
                }

                // Se for timestamp
                if (is_numeric($date)) {
                    return date('d/m/Y H:i', $date);
                }

            } catch (Exception $e) {
                return date('d/m/Y H:i');
            }

            return date('d/m/Y H:i');
        }
    @endphp

    <!-- Background -->
    <div class="certificate-bg"></div>
    <div class="bg-pattern"></div>

    <!-- Bordas principais -->
    <div class="certificate-border"></div>
    <div class="inner-border"></div>

    <!-- Elementos decorativos nos cantos -->
    <div class="corner-decoration corner-tl"></div>
    <div class="corner-decoration corner-tr"></div>
    <div class="corner-decoration corner-bl"></div>
    <div class="corner-decoration corner-br"></div>

    <!-- Linhas decorativas -->
    <div class="decorative-line line-top"></div>
    <div class="decorative-line line-bottom"></div>

    <!-- Brasão -->
    <div class="brasao-container">
        <div class="brasao">
            <div class="brasao-text">
                BRASÃO<br>
                DO<br>
                BRASIL
            </div>
        </div>
    </div>

    <!-- Selo de autenticidade -->
    <div class="authenticity-seal">
        SELO<br>
        OFICIAL
    </div>

    <!-- Conteúdo principal -->
    <div class="certificate-content">
        <div>
            <!-- Cabeçalho oficial -->
            <div class="header">
                <h1 class="republic">República Federativa do Brasil</h1>
                <h1>Estado do {{ $certificate->student->school->state ?? 'Piauí' }}</h1>
                <h1>Secretaria de Estado da Educação</h1>
            </div>

            <!-- Informações da escola -->
            <div class="school-section">
                <div class="cnpj-inep">
                    CNPJ Nº {{ $certificate->student->school->cnpj ?? '08.055.298/0001-49' }}
                    &nbsp;&nbsp;•&nbsp;&nbsp;
                    INEP Nº {{ $certificate->student->school->inep ?? '22136703' }}
                </div>

                <div class="school-info">
                    <strong>{{ strtoupper($certificate->student->school->name ?? 'Centro Estadual de Tempo Integral Francisca Trindade') }}</strong><br>
                    <em style="font-size: 2.5mm; color: #666;">Nome do Estabelecimento de Ensino</em><br>
                    <strong>{{ strtoupper($certificate->student->school->address ?? 'Rua do Arame, S/N – Bairro Santinho – CEP: 64.100-000 – Barras-PI') }}</strong><br>
                    <em style="font-size: 2.5mm; color: #666;">Endereço</em>
                </div>

                <div class="authorization">
                    Autorização de Funcionamento pela Resolução CEE/PI Nº {{ $certificate->student->school->authorization_number ?? '224/2022' }}
                    de {{ $certificate->student->school->authorization_date ?? '02/12/2022' }}
                </div>
            </div>

            <!-- Título do certificado -->
            <div class="certificate-title">
                <h1>Certificado de Conclusão do Ensino <span class="course-level">{{ strtoupper($certificate->course_level ?? 'Médio') }}</span></h1>
            </div>
        </div>

        <div class="main-content">
            <!-- Conteúdo principal -->
            <div class="content-text">
                A Direção do <span class="highlight">{{ $certificate->student->school->type ?? 'Centro Estadual de Tempo Integral - CETI' }}</span>
                <strong>{{ strtoupper($certificate->student->school->short_name ?? 'Francisca Trindade') }}</strong>,
                no uso de suas atribuições legais, confere a
                <span class="student-name">{{ strtoupper($certificate->student->name ?? 'Nome do Aluno') }}</span>,
                CPF {{ $certificate->student->cpf ?? '000.000.000-00' }},
                                                  nascido(a) em {{ formatDatePt($certificate->student->birth_date ?? '2000-01-01') }},
                natural de <span class="highlight">{{ strtoupper($certificate->student->birth_city ?? 'Barras') }}</span>,
                Estado {{ strtoupper($certificate->student->birth_state ?? 'do Piauí') }},
                nacionalidade <span class="highlight">{{ strtoupper($certificate->student->nationality ?? 'brasileira') }}</span>,
                filho(a) de <span class="highlight">{{ strtoupper($certificate->student->father_name ?? 'Não informado') }}</span>
                e de <span class="highlight">{{ strtoupper($certificate->student->mother_name ?? 'Não informado') }}</span>,
                o presente certificado por ter concluído no ano de <span class="highlight">{{ $certificate->completion_year ?? date('Y') }}</span>
                o Ensino <span class="course-level">{{ strtoupper($certificate->course_level ?? 'Médio') }}</span>,
                para que possa gozar de todos os direitos e prerrogativas concedidas pelas leis do País.
            </div>

            <!-- Local e data -->
            <div class="location-date">
                {{ strtoupper($certificate->student->school->city ?? 'Barras') }} - {{ strtoupper($certificate->student->school->state ?? 'PI') }},
                                                  {{ formatDatePt($certificate->issue_date ?? now()) }}.
            </div>
        </div>

        <div>
            <!-- Assinaturas -->
            <div class="signatures-section">
                <div class="signatures">
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-title">Secretário(a)</div>
                        <div class="signature-role">Secretaria de Educação</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-title">Diretor(a)</div>
                        <div class="signature-role">{{ $certificate->student->school->short_name ?? 'Escola' }}</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-title">Concluinte</div>
                        <div class="signature-role">Portador do Certificado</div>
                    </div>
                </div>
            </div>

            <!-- Rodapé -->
            <div class="footer">
                                                  <div class="issue-info">
                     Emitido digitalmente em {{ formatDateTimePt($certificate->created_at) }}
                 </div>
                <div class="verification-code">
                    Código de Verificação: {{ $certificate->verification_code ?? 'CERT-' . date('Y') . '-00001' }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
