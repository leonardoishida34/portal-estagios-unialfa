import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  console.log('Iniciando a semeadura do banco de dados (seed)...');

  const admin = await prisma.user.upsert({
    where: { login: 'admin' },
    update: {},
    create: {
      login: 'admin',         
      email: 'admin@unialfa.com.br',
      name: 'Administrador',
      password: 'senha_criptografada_aqui',
      role: 'ADMIN',
    },
  });

  console.log({ admin });
  console.log('Semeadura concluída com sucesso!');
}

main()
  .then(async () => {
    await prisma.$disconnect();
  })
  .catch(async (e) => {
    console.error(e);
    await prisma.$disconnect();
    process.exit(1);
  });