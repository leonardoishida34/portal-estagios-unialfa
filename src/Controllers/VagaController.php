<?php

require_once __DIR__ . '/../Services/VagaService.php';

class VagaController {
	private VagaService $service;

	public function __construct() {
		$this->service = new VagaService();
	}

	public function buscar(int $id): ?Vaga {
		return $this->service->buscarVaga($id);
	}
}
