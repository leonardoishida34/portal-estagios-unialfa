package br.com.unialfa.service;

import br.com.unialfa.dao.EmpresaDAO;
import br.com.unialfa.model.Empresa;

import java.util.List;

public class EmpresaService {

    private EmpresaDAO dao = new EmpresaDAO(); // O Service é o único que conversa com o DAO
    private br.com.unialfa.dao.VagaDAO vagaDAO = new br.com.unialfa.dao.VagaDAO();
    // Regra de Negócio para Salvar
    public void salvar(String razaoSocial, String cnpj, String email, String telefone) throws Exception {

        // 1. Validações (A inteligência está aqui agora)
        if (razaoSocial == null || razaoSocial.trim().isEmpty()) {
            throw new Exception("A Razão Social é de preenchimento obrigatório.");
        }
        if (cnpj == null || cnpj.trim().isEmpty()) {
            throw new Exception("O CNPJ é de preenchimento obrigatório.");
        }

        // 2. Prepara o objeto exatamente como você fazia na View
        Empresa novaEmpresa = new Empresa();
        novaEmpresa.setRazaoSocial(razaoSocial.trim());
        novaEmpresa.setCnpj(cnpj.trim());
        novaEmpresa.setEmail(email.trim());
        novaEmpresa.setTelefone(telefone.trim());
        novaEmpresa.setAprovada(true); // Mantemos a sua regra de aprovação automática

        // 3. Envia para o músculo (DAO) guardar no banco
        dao.salvar(novaEmpresa);
    }

    // Regra para Listar (Apenas faz a ponte)
    public List<Empresa> listarTodas() {
        return dao.listarTodas();
    }

    // Regra para Aprovar/Bloquear (Para a tela de Gestão)
    public void atualizarStatus(Long id, boolean aprovada) throws Exception {
        if (id == null) {
            throw new Exception("ID da empresa inválido.");
        }
        dao.atualizarStatus(id, aprovada);
    }
    // Regra para Excluir com Trava de Dependência
    public void excluir(Long id) throws Exception {
        if (id == null) {
            throw new Exception("ID da empresa inválido.");
        }

        // 1. Verifica se existem vagas amarradas a esta empresa
        if (vagaDAO.temVagasVinculadas(id)) {
            throw new Exception("LGPD Bloqueio: Não é possível excluir esta Empresa porque existem vagas vinculadas a ela.\nPor favor, exclua as vagas primeiro para manter a integridade dos dados.");
        }

        // 2. Se passou pela trava, pode excluir fisicamente
        dao.excluir(id);
    }
}