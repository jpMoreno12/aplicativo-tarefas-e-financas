# TaskFinance - Aplicação de Produtividade e Finanças (MVP)

Uma aplicação web completa para gerenciamento de produtividade pessoal e finanças, desenvolvida com Laravel e Blade templates.

## 🚀 Funcionalidades

### 📋 Gestão de Tarefas
- **CRUD completo** de tarefas com categorização
- **Sistema de subtarefas** para organização hierárquica
- **Datas de vencimento** com alertas de atraso
- **Sistema de recompensas** para motivação
- **Marcação de conclusão** com histórico
- **Filtros avançados** por categoria, meta e status

### 🎯 Gestão de Metas
- **Criação de metas** com descrições detalhadas
- **Vinculação de tarefas** às metas
- **Acompanhamento de progresso** visual
- **Dashboard de metas ativas**

### 🏷️ Categorização
- **Sistema de categorias** personalizáveis
- **Organização por contexto** (trabalho, pessoal, estudos, etc.)
- **Estatísticas por categoria**

### 💰 Controle Financeiro
- **Registro de despesas** com categorização
- **Definição de renda mensal**
- **Cálculo automático de saldo**
- **Relatórios por categoria**
- **Navegação por meses**
- **Gráficos de gastos**

### 📅 Calendário
- **Visualização mensal** de tarefas
- **Indicadores visuais** de status
- **Navegação entre meses**
- **Lista detalhada** de tarefas por data

### 📊 Dashboard
- **Resumo do dia** com tarefas pendentes
- **Metas ativas** em destaque
- **Tarefas atrasadas** com alertas
- **Histórico de conclusões**
- **Estatísticas rápidas**

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 10
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL/PostgreSQL/SQLite
- **Icons**: Bootstrap Icons
- **Styling**: Bootstrap 5 + CSS customizado

## 📦 Instalação e Configuração

### Pré-requisitos
- PHP 8.1+
- Composer
- MySQL/PostgreSQL/SQLite
- Node.js (opcional, para assets)

### Passo a Passo

1. **Clone o repositório**
```bash
git clone <repository-url>
cd tcc-app-tarefas-financas-main
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o ambiente**
```bash
# Copie o arquivo de ambiente (se não existir)
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate
```

4. **Configure o banco de dados**
Edite o arquivo `.env` com suas credenciais:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tcc_app_tarefas_financas
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Variáveis para futuras integrações
AUTHENTICATOR_URL=
AUTHORIZER_URL=
```

5. **Execute as migrações**
```bash
php artisan migrate
```

6. **Popule com dados de demonstração (opcional)**
```bash
php artisan db:seed --class=DemoSeeder
```

7. **Inicie o servidor**
```bash
php artisan serve
```

8. **Acesse a aplicação**
Abra seu navegador em `http://localhost:8000`

## 👤 Usuário de Demonstração

Se você executou o seeder, pode fazer login com:
- **Email**: demo@example.com
- **Senha**: password

## 🗂️ Estrutura do Projeto

### Models
- `User` - Usuários do sistema
- `Category` - Categorias de tarefas
- `Goal` - Metas/objetivos
- `Task` - Tarefas (com suporte a subtarefas)
- `Expense` - Despesas financeiras

### Controllers
- `DashboardController` - Dashboard principal
- `TaskController` - Gestão de tarefas
- `CategoryController` - Gestão de categorias
- `GoalController` - Gestão de metas
- `FinanceController` - Controle financeiro
- `CalendarController` - Visualização em calendário
- `Auth/LoginController` - Autenticação
- `Auth/RegisterController` - Registro de usuários

### Policies
Todas as models possuem policies configuradas (atualmente retornando `true` para todas as operações, preparadas para integração futura com serviços de autorização).

## 🔐 Autenticação

O sistema inclui autenticação básica com:
- **Login/Logout**
- **Registro de novos usuários**
- **Proteção de rotas** com middleware `auth`
- **Interface responsiva** para dispositivos móveis

## 📱 Interface Responsiva

A aplicação foi desenvolvida com Bootstrap 5, garantindo:
- **Design responsivo** para desktop, tablet e mobile
- **Sidebar colapsível** em dispositivos menores
- **Modais** para ações rápidas
- **Alertas e notificações** visuais
- **Ícones intuitivos** do Bootstrap Icons

## 🚀 Próximos Passos

Este MVP está preparado para futuras integrações:

1. **Microserviços de Autenticação/Autorização**
   - Variáveis de ambiente já configuradas
   - Policies preparadas para integração

2. **API REST**
   - Estrutura preparada para exposição de APIs
   - Controllers organizados para reutilização

3. **Notificações**
   - Sistema de alertas para tarefas vencidas
   - Lembretes por email/SMS

4. **Relatórios Avançados**
   - Gráficos interativos
   - Exportação de dados
   - Análises de produtividade

## 🤝 Contribuição

Este é um projeto acadêmico (TCC), mas contribuições são bem-vindas:

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 📞 Suporte

Para dúvidas ou suporte:
- Abra uma issue no GitHub
- Entre em contato através do email do projeto

---

**Desenvolvido com ❤️ usando Laravel**