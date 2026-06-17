const { Router } = require('express')
const c = require('../controllers/usuarioController')
const router = Router()
router.get('/', c.listar)
router.post('/login', c.login)
router.post('/', c.criar)
router.delete('/:id', c.remover)
module.exports = router
