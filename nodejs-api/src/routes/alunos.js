const { Router } = require('express')
const c = require('../controllers/alunoController')
const router = Router()
router.get('/', c.listar)
router.get('/:ra', c.buscarPorRa)
router.post('/', c.criar)
router.put('/:ra', c.atualizar)
router.delete('/:ra', c.remover)
module.exports = router
