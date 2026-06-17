import { Router } from 'express'
import { listar, buscarPorId, criar, atualizar, remover } from '../controllers/vagaController'
const router = Router()
router.get('/', listar)
router.get('/:id', buscarPorId)
router.post('/', criar)
router.put('/:id', atualizar)
router.delete('/:id', remover)
export default router
