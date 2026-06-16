const { Router } = require('express')
const empresaController = require('../controllers/empresaController')

const router = Router()

router.get('/', empresaController.listar)          // GET    /empresas
router.get('/:id', empresaController.buscarPorId)  // GET    /empresas/:id
router.post('/', empresaController.criar)          // POST   /empresas
router.put('/:id', empresaController.atualizar)    // PUT    /empresas/:id
router.delete('/:id', empresaController.remover)   // DELETE /empresas/:id

module.exports = router
