import express, { Application, Request, Response, NextFunction } from 'express'
import cors from 'cors'

import vagaRoutes from './routes/vagas'
import candidaturaRoutes from './routes/candidaturas'
import alunoRoutes from './routes/alunos'
import empresaRoutes from './routes/empresas'
import usuarioRoutes from './routes/usuarios'
import errorHandler from './middlewares/errorHandler'

const app: Application = express()

app.use(cors())
app.use(express.json())

// Serializa BigInt para Number nas respostas JSON
app.use((req: Request, res: Response, next: NextFunction) => {
  const originalJson = res.json.bind(res)
  res.json = (data: unknown) => {
    const serialized = JSON.parse(
      JSON.stringify(data, (_key, value) =>
        typeof value === 'bigint' ? Number(value) : value
      )
    )
    return originalJson(serialized)
  }
  next()
})

app.use('/api/vagas', vagaRoutes)
app.use('/api/candidaturas', candidaturaRoutes)
app.use('/api/alunos', alunoRoutes)
app.use('/api/empresas', empresaRoutes)
app.use('/api/usuarios', usuarioRoutes)

app.get('/', (_req: Request, res: Response) => {
  res.json({ message: 'API Portal de Estagios UniALFA', status: 'online' })
})

app.use(errorHandler)

export default app
