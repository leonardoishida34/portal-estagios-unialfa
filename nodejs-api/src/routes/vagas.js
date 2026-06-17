const { Router } = require('express')
const c = require('../controllers/vagaController')
const router = Router()
router.get('/', c.listar)
router.get('/:id', c.buscarPorId)
router.post('/', c.criar)
router.put('/:id', c.atualizar)
router.delete('/:id', c.remover)
module.exports = router
