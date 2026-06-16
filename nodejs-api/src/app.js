const express = require('express')
const cors = require('cors')

const vagaRoutes = require('./routes/vagas')
const candidaturaRoutes = require('./routes/candidaturas')
const alunoRoutes = require('./routes/alunos')
const empresaRoutes = require('./routes/empresas')
const errorHandler = require('./middlewares/errorHandler')

const app = express()

app.use(cors())
app.use(express.json())

// Rotas
app.use('/vagas', vagaRoutes)
app.use('/candidaturas', candidaturaRoutes)
app.use('/alunos', alunoRoutes)
app.use('/empresas', empresaRoutes)

// Health check
app.get('/', (req, res) => {
  res.json({ message: 'API Portal de Estágios UniALFA', status: 'online' })
})

// Middleware de erro (deve ser o último)
app.use(errorHandler)

module.exports = app
