const API = "http://127.0.0.1:8080/usuario";

// Carregar usuários ao iniciar
document.addEventListener("DOMContentLoaded", carregarUsuarios);

function carregarUsuarios() {
    fetch(API)
        .then(res => res.json())
        .then(usuarios => {
            const tabela = document.querySelector("#usuariosTabela");
            tabela.innerHTML = "";

            usuarios.forEach(u => {
                tabela.innerHTML += `
                    <tr>
                        <td>${u.id}</td>
                        <td>${u.nome}</td>
                        <td>${u.email}</td>
                        <td>${u.telefone || "-"}</td>
                        <td>${u.bairro || "-"}</td>
                        <td>
                            <button class="editar" onclick="editar(${u.id})">Editar</button>
                            <button class="excluir" onclick="remover(${u.id})">Excluir</button>
                        </td>
                    </tr>
                `;
            });
        });
}


// FORMULÁRIO - CRIAR OU EDITAR
document.querySelector("#userForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const id = document.querySelector("#id").value;

    const usuario = {
        nome: document.querySelector("#nome").value,
        email: document.querySelector("#email").value,
        telefone: document.querySelector("#telefone").value,
        senha: document.querySelector("#senha").value,
        bairro: document.querySelector("#bairro").value
    };

    if (id) {
        atualizar(id, usuario);
    } else {
        criar(usuario);
    }
});


// Criar usuário
function criar(usuario) {
    fetch(API, {
        method: "POST",
        body: JSON.stringify(usuario),
        headers: { "Content-Type": "application/json" }
    })
    .then(r => r.json())
    .then(() => {
        limparFormulario();
        carregarUsuarios();
    });
}


// Carregar usuário para editar
function editar(id) {
    fetch(`${API}/${id}`)
        .then(r => r.json())
        .then(u => {
            document.querySelector("#id").value = u.id;
            document.querySelector("#nome").value = u.nome;
            document.querySelector("#email").value = u.email;
            document.querySelector("#telefone").value = u.telefone;
            document.querySelector("#bairro").value = u.bairro;

            document.querySelector("#cancelarEdicao").style.display = "inline-block";
        });
}


// Atualizar usuário
function atualizar(id, usuario) {
    fetch(`${API}/${id}`, {
        method: "PUT",
        body: JSON.stringify(usuario),
        headers: { "Content-Type": "application/json" }
    })
    .then(r => r.json())
    .then(() => {
        limparFormulario();
        carregarUsuarios();
    });
}


// Excluir usuário
function remover(id) {
    if (!confirm("Deseja realmente excluir?")) return;

    fetch(`${API}/${id}`, { method: "DELETE" })
        .then(r => r.json())
        .then(() => carregarUsuarios());
}


// Limpar o formulário
function limparFormulario() {
    document.querySelector("#id").value = "";
    document.querySelector("#userForm").reset();
    document.querySelector("#cancelarEdicao").style.display = "none";
}
