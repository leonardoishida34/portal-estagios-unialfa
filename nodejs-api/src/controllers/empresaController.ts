import { Request, Response, NextFunction } from 'express'
import {
  listarEmpresas,
  buscarEmpresaPorId,
  criarEmpresa,
  atualizarEmpresa,
  removerEmpresa,
  loginEmpresa
} from '../services/empresaService'

export async function listar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await listarEmpresas(req.query.aprovada as string | undefined))
  } catch (err) { next(err) }
}

export async function buscarPorId(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await buscarEmpresaPorId(BigInt(req.params.id)))
  } catch (err) { next(err) }
}

export async function criar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.status(201).json(await criarEmpresa(req.body))
  } catch (err) { next(err) }
}

export async function atualizar(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    res.json(await atualizarEmpresa(BigInt(req.params.id), req.body))
  } catch (err) { next(err) }
}

export async function remover(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    await removerEmpresa(BigInt(req.params.id))
    res.status(204).send()
  } catch (err) { next(err) }
}

export async function login(req: Request, res: Response, next: NextFunction): Promise<void> {
  try {
    const { email, senha } = req.body
    if (!email || !senha) { res.status(400).json({ error: 'E-mail e senha sao obrigatorios' }); return }
    const empresa = await loginEmpresa(email, senha)
    res.json(empresa)
  } catch (err: any) {
    res.status(401).json({ error: err.message })
  }
}
