import { Router } from 'express'
import { listar, buscarPorId, criar, atualizarStatus, remover } from '../controllers/candidaturaController'
const router = Router()
router.get('/', listar)
router.get('/:id', buscarPorId)
router.post('/', criar)
router.patch('/:id/status', atualizarStatus)
router.delete('/:id', remover)
export default router
