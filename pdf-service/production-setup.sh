#!/bin/bash

echo "🚀 Configurando PDF Service em Produção - Linux"

# Verificar se está no diretório correto
if [ ! -f "package.json" ]; then
    echo "❌ Execute este script na pasta pdf-service"
    exit 1
fi

# Instalar dependências
echo "📦 Instalando dependências..."
npm install --production

# Instalar PM2 globalmente
echo "🔧 Instalando PM2..."
sudo npm install -g pm2

# Configurar variáveis de ambiente para produção
echo "⚙️ Configurando ambiente..."
export NODE_ENV=production
export PORT=3001

# Testar se Puppeteer funciona
echo "🧪 Testando Puppeteer..."
node -e "
const puppeteer = require('puppeteer');
(async () => {
  try {
    const browser = await puppeteer.launch({
      headless: 'new',
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--disable-extensions',
        '--disable-plugins',
        '--no-first-run',
        '--no-default-browser-check'
      ]
    });
    console.log('✅ Puppeteer funcionando!');
    await browser.close();
    process.exit(0);
  } catch (error) {
    console.error('❌ Erro no Puppeteer:', error.message);
    process.exit(1);
  }
})();
"

if [ $? -eq 0 ]; then
    echo "✅ Puppeteer configurado corretamente!"
else
    echo "❌ Erro na configuração do Puppeteer"
    echo "💡 Verifique se todas as dependências foram instaladas"
    exit 1
fi

# Configurar PM2
echo "🔧 Configurando PM2..."
pm2 delete pdf-service 2>/dev/null || true
pm2 start server.js --name "pdf-service" --instances 1

# Configurar inicialização automática
echo "🔄 Configurando inicialização automática..."
pm2 startup
pm2 save

# Configurar firewall (se necessário)
echo "🔥 Configurando firewall..."
sudo firewall-cmd --permanent --add-port=3001/tcp 2>/dev/null || echo "Firewall não configurado (opcional)"
sudo firewall-cmd --reload 2>/dev/null || echo "Firewall reload pulado"

# Testar o serviço
echo "🧪 Testando serviço..."
sleep 3
curl -s http://localhost:3001/health | grep -q "PDF Service is running"

if [ $? -eq 0 ]; then
    echo "✅ PDF Service configurado com sucesso!"
    echo "🌐 Acesse: http://$(hostname -I | awk '{print $1}'):3001/health"
    echo "📊 Monitorar: pm2 logs pdf-service"
    echo "🔄 Restart: pm2 restart pdf-service"
else
    echo "❌ Erro na configuração do serviço"
    echo "📋 Verifique os logs: pm2 logs pdf-service"
fi
