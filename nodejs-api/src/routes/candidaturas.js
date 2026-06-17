const { Router } = require('express')
const c = require('../controllers/candidaturaController')
const router = Router()
router.get('/', c.listar)
router.get('/:id', c.buscarPorId)
router.post('/', c.criar)
router.patch('/:id/status', c.atualizarStatus)
router.delete('/:id', c.remover)
module.exports = router
