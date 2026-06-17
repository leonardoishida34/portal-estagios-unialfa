import { Router } from 'express'
import { listar, buscarPorRa, criar, atualizar, remover } from '../controllers/alunoController'
const router = Router()
router.get('/', listar)
router.get('/:ra', buscarPorRa)
router.post('/', criar)
router.put('/:ra', atualizar)
router.delete('/:ra', remover)
export default router
