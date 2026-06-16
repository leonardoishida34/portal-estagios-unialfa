const { ZodError } = require('zod')

function errorHandler(err, req, res, next) {
  if (err instanceof ZodError) {
    return res.status(400).json({
      error: 'Dados inválidos',
      detalhes: err.errors.map(e => ({ campo: e.path.join('.'), mensagem: e.message }))
    })
  }

  if (err.code === 'P2002') {
    return res.status(409).json({ error: 'Registro já existe (campo único duplicado)' })
  }
  if (err.code === 'P2025') {
    return res.status(404).json({ error: 'Registro não encontrado' })
  }

  console.error(err)
  res.status(500).json({ error: 'Erro interno do servidor' })
}

module.exports = errorHandler
