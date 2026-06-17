import { Request, Response, NextFunction } from 'express'
import {
  listarUsuarios,
  loginUsuario,
  criarUsuario,
  removerUsuario
} from '../services/usuarioService'

export async function listar(_req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await listarUsuarios())
  } catch (err) { next(err) }
}

export async function login(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { login, senha } = req.body
    res.json(await loginUsuario(login, senha))
  } catch (err: any) {
    res.status(401).json({ error: err.message })
  }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.status(201).json(await criarUsuario(req.body))
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await removerUsuario(BigInt(req.params.id))
    res.status(204).send()
  } catch (err) { next(err) }
}
