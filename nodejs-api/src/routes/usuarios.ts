import { Router } from 'express'
import { listar, login, criar, remover } from '../controllers/usuarioController'
const router = Router()
router.get('/', listar)
router.post('/login', login)
router.post('/', criar)
router.delete('/:id', remover)
export default router
