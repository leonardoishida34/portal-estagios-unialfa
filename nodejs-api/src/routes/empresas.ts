import { Router } from 'express'
import { listar, buscarPorId, criar, atualizar, remover, login } from '../controllers/empresaController'

const router = Router()

router.get('/',        listar)
router.get('/:id',     buscarPorId)
router.post('/',       criar)
router.post('/login',  login)
router.put('/:id',     atualizar)
router.delete('/:id',  remover)

export default router
