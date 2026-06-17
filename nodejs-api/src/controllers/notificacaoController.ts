import { Request, Response, NextFunction } from 'express'
import {
  listarNotificacoes,
  contarNaoLidas,
  marcarComoLida,
  marcarTodasComoLidas
} from '../services/notificacaoService'

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { aluno_ra } = req.query
    if (!aluno_ra || typeof aluno_ra !== 'string') {
      res.status(400).json({ error: 'Parametro aluno_ra e obrigatorio' })
      return
    }
    const notificacoes = await listarNotificacoes(aluno_ra)
    res.json(notificacoes)
  } catch (err) { next(err) }
}

export async function contarNaoLidasHandler(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { aluno_ra } = req.query
    if (!aluno_ra || typeof aluno_ra !== 'string') {
      res.status(400).json({ error: 'Parametro aluno_ra e obrigatorio' })
      return
    }
    const total = await contarNaoLidas(aluno_ra)
    res.json({ total })
  } catch (err) { next(err) }
}

export async function marcarLida(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const notificacao = await marcarComoLida(BigInt(req.params.id))
    res.json(notificacao)
  } catch (err) { next(err) }
}

export async function marcarTodasLidas(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { aluno_ra } = req.body
    if (!aluno_ra) {
      res.status(400).json({ error: 'Campo aluno_ra e obrigatorio' })
      return
    }
    await marcarTodasComoLidas(aluno_ra)
    res.json({ mensagem: 'Todas as notificacoes marcadas como lidas' })
  } catch (err) { next(err) }
}
