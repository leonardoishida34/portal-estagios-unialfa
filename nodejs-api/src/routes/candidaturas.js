const { Router } = require('express')
const candidaturaController = require('../controllers/candidaturaController')

const router = Router()

router.get('/', candidaturaController.listar)                      // GET    /candidaturas
router.get('/:id', candidaturaController.buscarPorId)              // GET    /candidaturas/:id
router.post('/', candidaturaController.criar)                      // POST   /candidaturas
router.patch('/:id/status', candidaturaController.atualizarStatus) // PATCH  /candidaturas/:id/status
router.delete('/:id', candidaturaController.remover)               // DELETE /candidaturas/:id

module.exports = router
