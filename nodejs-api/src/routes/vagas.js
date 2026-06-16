const { Router } = require('express')
const vagaController = require('../controllers/vagaController')

const router = Router()

router.get('/', vagaController.listar)           // GET    /vagas
router.get('/:id', vagaController.buscarPorId)   // GET    /vagas/:id
router.post('/', vagaController.criar)           // POST   /vagas
router.put('/:id', vagaController.atualizar)     // PUT    /vagas/:id
router.delete('/:id', vagaController.remover)    // DELETE /vagas/:id

module.exports = router
