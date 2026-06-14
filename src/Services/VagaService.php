<?php

// ============================================
// SERVICE: VagaService
// Responsável pelas chamadas à API relacionadas
// às vagas de estágio. Usa o ApiClient para
// fazer as requisições e converte os dados
// retornados em objetos Vaga.
// ============================================
require_once BASE_PATH . '/src/Services/ApiClient.php';
require_once BASE_PATH . '/src/Models/Vaga.php';

class VagaService {

    // ── Dependência ──
    // Usa o ApiClient para fazer as requisições HTTP
    private ApiClient $client;

    public function __construct() {
        $this->client = new ApiClient();
    }

    // ── Listar todas as vagas ──
    // Faz GET /vagas na API
    // Retorna array de objetos Vaga
    public function listarVagas(): array {
        $data = $this->client->get('/vagas');

        // Se deu erro, retorna array vazio
        if (isset($data['erro'])) return [];

        // Converte cada item do array em um objeto Vaga
        // array_map percorre o array e aplica a função em cada item
        return array_map(fn($item) => new Vaga($item), $data);
    }

    // ── Buscar uma vaga específica ──
    // Faz GET /vagas/{id} na API
    // Retorna objeto Vaga ou null se não encontrar
    public function buscarVaga(int $id): ?Vaga {
        $data = $this->client->get("/vagas/{$id}");

        // Se não encontrou ou deu erro
        if (isset($data['erro']) || !isset($data['id'])) return null;

        return new Vaga($data);
    }

    // ── Criar nova vaga ──
    // Faz POST /vagas na API com os dados da vaga
    // Retorna a resposta da API (sucesso ou erro)
    public function criarVaga(array $dados): array {
        return $this->client->post('/vagas', $dados);
    }

    // ── Atualizar vaga existente ──
    // Faz PUT /vagas/{id} na API com os novos dados
    // Retorna a resposta da API (sucesso ou erro)
    public function atualizarVaga(int $id, array $dados): array {
        return $this->client->put("/vagas/{$id}", $dados);
    }

    // ── Excluir vaga ──
    // Faz DELETE /vagas/{id} na API
    // Retorna a resposta da API (sucesso ou erro)
    public function excluirVaga(int $id): array {
        return $this->client->delete("/vagas/{$id}");
    }

    // ── Listar vagas de uma empresa ──
    // Faz GET /vagas?empresa_id={id} na API
    // Usado no painel da empresa para mostrar só as vagas dela
    public function listarPorEmpresa(int $empresaId): array {
        $data = $this->client->get("/vagas?empresa_id={$empresaId}");

        if (isset($data['erro'])) return [];

        return array_map(fn($item) => new Vaga($item), $data);
    }
}
