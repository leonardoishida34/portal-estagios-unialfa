import { Request, Response, NextFunction } from 'express'
import { listarVagas, buscarVagaPorId, criarVaga, atualizarVaga, removerVaga } from '../services/vagaService'

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await listarVagas(
      req.query.ativa      as string | undefined,
      req.query.empresa_id as string | undefined
    ))
  } catch (err) { next(err) }
}

export async function buscarPorId(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await buscarVagaPorId(BigInt(req.params.id)))
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.status(201).json(await criarVaga(req.body))
  } catch (err) { next(err) }
}

export async function atualizar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await atualizarVaga(BigInt(req.params.id), req.body))
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await removerVaga(BigInt(req.params.id))
    res.status(204).send()
  } catch (err) { next(err) }
}
