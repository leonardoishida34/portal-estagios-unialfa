import { Request, Response, NextFunction } from 'express'
import { ZodError } from 'zod'

export default function errorHandler(
  err: Error & { code?: string },
  _req: Request,
  res: Response,
  _next: NextFunction
): void {
  if (err instanceof ZodError) {
    res.status(400).json({
      error: 'Dados invalidos',
      detalhes: err.errors.map(e => ({ campo: e.path.join('.'), mensagem: e.message }))
    })
    return
  }
  if (err.code === 'P2002') { res.status(409).json({ error: 'Registro ja existe' }); return }
  if (err.code === 'P2025') { res.status(404).json({ error: 'Registro nao encontrado' }); return }
  console.error(err)
  res.status(500).json({ error: 'Erro interno do servidor' })
}
