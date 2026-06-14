const { Router } = require('express')
const alunoController = require('../controllers/alunoController')

const router = Router()

router.get('/', alunoController.listar)           // GET    /alunos
router.get('/:id', alunoController.buscarPorId)   // GET    /alunos/:id
router.post('/', alunoController.criar)           // POST   /alunos
router.put('/:id', alunoController.atualizar)     // PUT    /alunos/:id
router.delete('/:id', alunoController.remover)    // DELETE /alunos/:id

module.exports = router
