import { Router } from 'express'
import { listar, contarNaoLidasHandler, marcarLida, marcarTodasLidas } from '../controllers/notificacaoController'

const router = Router()

router.get('/',           listar)
router.get('/nao-lidas',  contarNaoLidasHandler)
router.patch('/:id/lida', marcarLida)
router.patch('/lidas',    marcarTodasLidas)

export default router
