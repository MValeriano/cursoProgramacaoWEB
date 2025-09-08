# 🌸 Feirinha Virtual de Talentos Locais

## 📖 Documento de Especificação  

### 1. História do Usuário (User Story)
> “Eu sou uma pessoa que faz artesanato. Tenho dificuldade de divulgar meu trabalho porque não sei criar sites e não tenho dinheiro para pagar por isso. Gostaria de ter um espaço simples, onde eu mesma pudesse cadastrar meus produtos, colocar foto, descrição e contato, para que outras pessoas possam encontrar e me procurar. Assim eu consigo divulgar meu talento e aumentar minhas chances de renda.”

---

### 2. Caso de Uso Principal — Catálogo de Produtos e Serviços  

**Atores:**
- **Usuária Anônima (Visitante):** acessa o site para ver a feirinha.  
- **Usuária Cadastradora (Expositora):** cadastra produtos ou serviços.  
- **Sistema:** site da feirinha (frontend + backend + banco de dados).  

**Fluxo Principal:**
1. A usuária acessa a página inicial da feirinha.  
2. Visualiza produtos/serviços já cadastrados em formato de cartões (nome, foto, descrição, contato).  
3. Para cadastrar algo, acessa o formulário de cadastro.  
4. Preenche os campos:
   - Nome do produto/serviço  
   - Categoria (artesanato, culinária, costura, etc.)  
   - Descrição curta  
   - Foto (upload ou link)  
   - Contato (WhatsApp, telefone ou e-mail)  
5. Clica em **Salvar**.  
6. O sistema grava no banco de dados e mostra o novo produto na vitrine.  
7. Visitantes podem usar filtros por categoria (JS) para visualizar só o que interessa.  

**Extensões Futuras (opcionais):**
- Login simples para que cada aluna edite apenas seus produtos.  
- Favoritos com **localStorage**.  
- Busca por nome ou palavra-chave.  

---

### 3. Telas Necessárias  

#### 3.1 Página inicial (vitrine)  
- Cabeçalho com título: **“Feirinha Virtual de Talentos Locais”**  
- Barra de filtros (dropdown com categorias).  
- Lista de produtos em cartões:  
  - Foto  
  - Nome do produto  
  - Categoria  
  - Descrição curta  
  - Contato  

#### 3.2 Tela de cadastro  
- Formulário com campos:  
  - Nome do produto/serviço  
  - Categoria (select)  
  - Descrição curta  
  - Upload/link de imagem  
  - Contato  
- Botão **Salvar**  
- Mensagem de sucesso ao cadastrar.  

#### 3.3 Tela de login (opcional/futuro)  
- Campos: usuário e senha.  
- Redirecionamento para área de cadastro.  

---

### 4. Banco de Dados  

**Tabela: produtos**  
| Campo      | Tipo       | Descrição                         |
|------------|-----------|------------------------------------|
| id         | INTEGER PK| Identificador único                |
| nome       | TEXT      | Nome do produto/serviço            |
| categoria  | TEXT      | Categoria (artesanato, culinária)  |
| descricao  | TEXT      | Breve descrição                    |
| imagem     | TEXT      | Caminho/link da imagem             |
| contato    | TEXT      | Telefone, WhatsApp ou e-mail       |
| criado_em  | DATETIME  | Data/hora de cadastro              |

**(Opcional Futuro) Tabela: usuarios**  
| Campo      | Tipo       | Descrição                         |
|------------|-----------|------------------------------------|
| id         | INTEGER PK| Identificador único                |
| nome       | TEXT      | Nome da usuária                    |
| email      | TEXT      | E-mail para login                  |
| senha      | TEXT      | Senha (criptografada)              |

---

👩‍💻 **Objetivo do projeto:**  
Criar, de forma colaborativa e prática, uma **plataforma simples de divulgação de talentos e serviços locais**, com foco em empoderamento, renda e autonomia para trabalhadores informais.  
