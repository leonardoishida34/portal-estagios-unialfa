package br.com.unialfa.service;

import br.com.unialfa.dao.UsuarioDAO;
import br.com.unialfa.model.Usuario;

import java.util.List;

public class UsuarioService {

    private UsuarioDAO dao = new UsuarioDAO();

    // 1. Regra para Autenticação
    public Usuario autenticar(String login, String senha) throws Exception {
        if (login == null || login.trim().isEmpty() || senha == null || senha.trim().isEmpty()) {
            throw new Exception("Por favor, preencha o login e a palavra-passe.");
        }
        return dao.autenticar(login, senha);
    }

    // 2. Regra para Salvar ou Editar
    public void salvarOuEditar(Long id, String nome, String login, String senha, String perfil, boolean isEdicao) throws Exception {

        // Validações obrigatórias
        if (nome == null || nome.trim().isEmpty()) throw new Exception("O Nome é obrigatório.");
        if (login == null || login.trim().isEmpty()) throw new Exception("O Login é obrigatório.");
        if (perfil == null || perfil.trim().isEmpty()) throw new Exception("Selecione um Perfil (ADMIN ou OPERADOR).");

        // Se for um cadastro novo, a senha é obrigatória. Na edição, pode ser mantida em branco para não alterar.
        if (!isEdicao && (senha == null || senha.trim().isEmpty())) {
            throw new Exception("A palavra-passe é obrigatória para novos utilizadores.");
        }

        Usuario u = new Usuario(id, nome.trim(), login.trim(), senha, perfil);

        if (isEdicao) {
            dao.atualizar(u);
        } else {
            dao.salvar(u);
        }
    }

    // 3. Regra para Listar
    public List<Usuario> listarTodos() {
        return dao.listarTodos();
    }

    // 4. Regra para Excluir (Físico / LGPD)
    public void excluir(Long id) throws Exception {
        if (id == null) {
            throw new Exception("ID de utilizador inválido para exclusão.");
        }
        // Futuramente, se houver dependências (ex: "quem cadastrou a vaga"), faríamos a verificação aqui.
        // Como o usuário no nosso sistema atual não "prende" nenhuma vaga, podemos apagar direto.
        dao.excluir(id);
    }
}