package br.com.unialfa.service;

import br.com.unialfa.dao.CandidaturaDAO;
import java.util.List;

public class CandidaturaService {

    private CandidaturaDAO dao = new CandidaturaDAO();

    // Regra para Listar
    public List<Object[]> listarTodas() {
        return dao.listarTodas();
    }

    // Regra para Mudar Status
    public void atualizarStatus(Long id, String novoStatus) throws Exception {
        if (id == null) {
            throw new Exception("ID da candidatura inválido.");
        }
        if (novoStatus == null || novoStatus.trim().isEmpty()) {
            throw new Exception("Status inválido.");
        }
        dao.atualizarStatus(id, novoStatus);
    }
}