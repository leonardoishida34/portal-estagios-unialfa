const express = require('express')
const cors = require('cors')

const vagaRoutes = require('./routes/vagas')
const candidaturaRoutes = require('./routes/candidaturas')
const alunoRoutes = require('./routes/alunos')
const empresaRoutes = require('./routes/empresas')
const usuarioRoutes = require('./routes/usuarios')
const errorHandler = require('./middlewares/errorHandler')

const app = express()

app.use(cors())
app.use(express.json())

// Serializa BigInt para Number nas respostas JSON
app.use((req, res, next) => {
  const originalJson = res.json.bind(res)
  res.json = (data) => {
    const serialized = JSON.parse(
      JSON.stringify(data, (key, value) =>
        typeof value === 'bigint' ? Number(value) : value
      )
    )
    return originalJson(serialized)
  }
  next()
})

app.use('/vagas', vagaRoutes)
app.use('/candidaturas', candidaturaRoutes)
app.use('/alunos', alunoRoutes)
app.use('/empresas', empresaRoutes)
app.use('/usuarios', usuarioRoutes)

app.get('/', (req, res) => {
  res.json({ message: 'API Portal de Estagios UniALFA', status: 'online' })
})

app.use(errorHandler)

module.exports = app
