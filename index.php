<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: quiz.php");
    exit;
}
?>

<html><head>
<meta charset="utf-8"/>
<link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
<link as="style" href="https://fonts.googleapis.com/css2?display=swap&amp;family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<title>Qapnaprova - Sobre Nós</title>
<link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
</head>
<body class="bg-[#121212]">
<div class="relative flex size-full min-h-screen flex-col group/design-root overflow-x-hidden" style='font-family: Inter, "Noto Sans", sans-serif;'>
<div class="layout-container flex h-full grow flex-col">
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-700 bg-[#1e1e1e] px-6 sm:px-10 py-4 shadow-sm">
<div class="flex items-center gap-3 text-slate-100">
<svg class="h-8 w-8 text-[#28a745]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_6_543)">
<path d="M42.1739 20.1739L27.8261 5.82609C29.1366 7.13663 28.3989 10.1876 26.2002 13.7654C24.8538 15.9564 22.9595 18.3449 20.6522 20.6522C18.3449 22.9595 15.9564 24.8538 13.7654 26.2002C10.1876 28.3989 7.13663 29.1366 5.82609 27.8261L20.1739 42.1739C21.4845 43.4845 24.5355 42.7467 28.1133 40.548C30.3042 39.2016 32.6927 37.3073 35 35C37.3073 32.6927 39.2016 30.3042 40.548 28.1133C42.7467 24.5355 43.4845 21.4845 42.1739 20.1739Z" fill="currentColor"></path>
<path clip-rule="evenodd" d="M7.24189 26.4066C7.31369 26.4411 7.64204 26.5637 8.52504 26.3738C9.59462 26.1438 11.0343 25.5311 12.7183 24.4963C14.7583 23.2426 17.0256 21.4503 19.238 19.238C21.4503 17.0256 23.2426 14.7583 24.4963 12.7183C25.5311 11.0343 26.1438 9.59463 26.3738 8.52504C26.5637 7.64204 26.4411 7.31369 26.4066 7.24189C26.345 7.21246 26.143 7.14535 25.6664 7.1918C24.9745 7.25925 23.9954 7.5498 22.7699 8.14278C20.3369 9.32007 17.3369 11.4915 14.4142 14.4142C11.4915 17.3369 9.32007 20.3369 8.14278 22.7699C7.5498 23.9954 7.25925 24.9745 7.1918 25.6664C7.14534 26.143 7.21246 26.345 7.24189 26.4066ZM29.9001 10.7285C29.4519 12.0322 28.7617 13.4172 27.9042 14.8126C26.465 17.1544 24.4686 19.6641 22.0664 22.0664C19.6641 24.4686 17.1544 26.465 14.8126 27.9042C13.4172 28.7617 12.0322 29.4519 10.7285 29.9001L21.5754 40.747C21.6001 40.7606 21.8995 40.931 22.8729 40.7217C23.9424 40.4916 25.3821 39.879 27.0661 38.8441C29.1062 37.5904 31.3734 35.7982 33.5858 33.5858C35.7982 31.3734 37.5904 29.1062 38.8441 27.0661C39.879 25.3821 40.4916 23.9425 40.7216 22.8729C40.931 21.8995 40.7606 21.6001 40.747 21.5754L29.9001 10.7285ZM29.2403 4.41187L43.5881 18.7597C44.9757 20.1473 44.9743 22.1235 44.6322 23.7139C44.2714 25.3919 43.4158 27.2666 42.252 29.1604C40.8128 31.5022 38.8165 34.012 36.4142 36.4142C34.012 38.8165 31.5022 40.8128 29.1604 42.252C27.2666 43.4158 25.3919 44.2714 23.7139 44.6322C22.1235 44.9743 20.1473 44.9757 18.7597 43.5881L4.41187 29.2403C3.29027 28.1187 3.08209 26.5973 3.21067 25.2783C3.34099 23.9415 3.8369 22.4852 4.54214 21.0277C5.96129 18.0948 8.43335 14.7382 11.5858 11.5858C14.7382 8.43335 18.0948 5.9613 21.0277 4.54214C22.4852 3.8369 23.9415 3.34099 25.2783 3.21067C26.5973 3.08209 28.1187 3.29028 29.2403 4.41187Z" fill="currentColor" fill-rule="evenodd"></path>
</g>
<defs>
<clipPath id="clip0_6_543">
<rect fill="white" height="48" width="48"></rect>
</clipPath>
</defs>
</svg>
<h2 class="text-xl font-bold leading-tight tracking-[-0.015em] text-slate-100">Qapnaprova</h2>
</div>
<nav class="hidden sm:flex flex-1 justify-end gap-3 md:gap-6">
<div class="flex items-center gap-3 md:gap-6">
<a class="text-[#28a745] text-sm font-semibold leading-normal" href="#">Informações</a>
</div>
<div class="flex gap-2">
  <a href="cadastro.html">
    <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#28a745] text-white text-sm font-bold leading-normal tracking-[0.015em] transition-all duration-300 transform hover:scale-105 hover:shadow-md hover:bg-green-600">
      <span class="truncate">Cadastrar</span>
    </button>
  </a>
  <a href="formlogin.php">
    <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-slate-700 text-slate-100 text-sm font-bold leading-normal tracking-[0.015em] transition-all duration-300 transform hover:scale-105 hover:shadow-md hover:bg-slate-600">
      <span class="truncate">Login</span>
    </button>
  </a>
</div>
</nav>
<button class="sm:hidden p-2 rounded-md text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#28a745]">
<span class="material-icons">menu</span>
</button>
</header>
<main class="px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 flex flex-1 justify-center py-8 sm:py-12 bg-[#121212]">
<div class="layout-content-container flex flex-col max-w-4xl flex-1 bg-[#1e1e1e] shadow-xl rounded-xl p-6 sm:p-8 md:p-12">
<div class="flex flex-wrap justify-between items-center gap-4 pb-6 border-b border-slate-700 mb-6">
<h1 class="text-slate-100 tracking-tight text-3xl sm:text-4xl font-bold leading-tight">Sobre o Qapnaprova</h1>
</div>
<p class="text-slate-300 text-base font-normal leading-relaxed mb-8">
              O QAPnaProva é uma plataforma desenvolvida com o objetivo de auxiliar profissionais da área policial na preparação para provas internas e processos seletivos específicos da corporação. Sabemos que essas provas exigem um conhecimento técnico e direcionado, por isso criamos um sistema de quiz interativo e especializado, com perguntas cuidadosamente selecionadas de acordo com os conteúdos cobrados nesses exames.<br>
A plataforma oferece um ambiente simples, direto e eficiente para que policiais possam testar seus conhecimentos, identificar pontos de melhoria e evoluir nos estudos de maneira prática. Todas as perguntas são voltadas exclusivamente para o contexto da prova interna, o que torna o QAPnaProva uma ferramenta extremamente útil e focada.
Nosso compromisso é com o fortalecimento do conhecimento, da capacitação e do desenvolvimento profissional dentro da instituição. Mais do que uma simples plataforma de perguntas e respostas, o QAPnaProva é um aliado estratégico para quem está QAP e quer alcançar novos patamares dentro da carreira policial.</p>
<h2 class="text-slate-100 text-2xl sm:text-3xl font-bold leading-tight tracking-tight mb-6 pt-2 border-t border-slate-700 mt-4">Conheça o Criador</h2>
<div class="flex flex-col sm:flex-row items-center gap-6 mb-8 p-6 bg-[#121212] rounded-lg shadow-sm">
<div class="flex-shrink-0">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-32 w-32 sm:h-36 sm:w-36 border-4 border-[#1e1e1e] shadow-md" style='background-image: url("https://avatars.githubusercontent.com/u/120068252?s=400&u=defefcbcca16ed825b9c1466872bee3e770f04b6&v=4");'></div>
</div>
<div class="text-center sm:text-left">
<p class="text-slate-100 text-xl sm:text-2xl font-semibold leading-tight tracking-tight">Gabriel Cagnin Guillen</p>
<p class="text-slate-400 text-base font-normal leading-normal mt-1">Engenheiro de Software</p>
<div class="flex justify-center sm:justify-start gap-4 mt-4">
<a class="flex items-center gap-2 text-sm font-medium text-slate-300 hover:text-[#28a745] bg-slate-700 hover:bg-slate-600 transition-colors px-4 py-2 rounded-md shadow-sm" href="https://github.com/devguillen" rel="noopener noreferrer" target="_blank">

<path clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.036 1.531 1.036.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.026 2.747-1.026.546 1.379.201 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.338 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.001 10.001 0 0022 12.017C22 6.484 17.522 2 12 2Z" fill-rule="evenodd"></path>
</svg>
                    
<svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
<path fill-rule="evenodd" clip-rule="evenodd"
  d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 
  9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369
  -1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069
  -.608 1.003.07 1.531 1.036 1.531 1.036.892 1.53 2.341 1.088 
  2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113
  -4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446
  -1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 
  0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 
  2.747-1.026 2.747-1.026.546 1.379.201 2.398.1 
  2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.338 
  4.695-4.566 4.943.359.309.678.92.678 
  1.855 0 1.338-.012 2.419-.012 2.747 0 
  .268.18.58.688.482A10.001 10.001 0 0022 
  12.017C22 6.484 17.522 2 12 2Z" />
</svg>
GitHub
                  </a>
<a class="flex items-center gap-2 text-sm font-medium text-slate-300 hover:text-[#28a745] bg-slate-700 hover:bg-slate-600 transition-colors px-4 py-2 rounded-md shadow-sm" href="https://www.linkedin.com/in/devguillen/" rel="noopener noreferrer" target="_blank">

<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
</svg>
                    
<svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
<path d="M19 0h-14c-2.761 0-5 2.239-5 
5v14c0 2.761 2.239 5 5 5h14c2.762 
0 5-2.239 5-5v-14c0-2.761-2.238
-5-5-5zm-11 19h-3v-11h3v11zm-1.5
-12.268c-.966 0-1.75-.79-1.75
-1.764s.784-1.764 1.75-1.764 
1.75.79 1.75 1.764-.783 
1.764-1.75 1.764zm13.5 12.268h
-3v-5.604c0-3.368-4-3.113-4 
0v5.604h-3v-11h3v1.765c1.396
-2.586 7-2.777 7 2.476v6.759z"/>
</svg>
LinkedIn
                  </a>


<a class="flex items-center gap-2 text-sm font-medium text-slate-300 hover:text-[#28a745] bg-slate-700 hover:bg-slate-600 transition-colors px-4 py-2 rounded-md shadow-sm" href="https://drive.google.com/file/d/1iTsbKpa1woOatrqkr2mVzMBpOaTAxmm0/view?usp=sharing" rel="noopener noreferrer" target="_blank">
  <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
    <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 
    1.1.9 2 2 2h12c1.1 0 2-.9 
    2-2V8l-6-6zm2 16H8v-2h8v2zm0
    -4H8v-2h8v2zm-3-5V3.5L18.5 
    9H13z"/>
  </svg>
  <span>Currículo</span>
</a>


</div>
<svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">

</p>

</div>
</div>
<p class="text-slate-300 text-base font-normal leading-relaxed mt-6">
  Sou estudante de Análise e Desenvolvimento de Sistemas na FATEC Ipiranga, com formação técnica pela ETEC. Sou apaixonado por desenvolvimento backend e busco aplicar meus conhecimentos em projetos que envolvam lógica, estrutura e integração de sistemas.
</p>
<p class="text-slate-300 text-base font-normal leading-relaxed mt-4">
  Tenho experiência com linguagens como PHP, Java, Python e SQL, além de trabalhar com bancos de dados MySQL e PostgreSQL. Domino versionamento com Git, metodologias ágeis como SCRUM e tenho familiaridade com arquitetura de sistemas, padrões de projeto e microsserviços.
</p>
<p class="text-slate-300 text-base font-normal leading-relaxed mt-4">
  Embora meu foco seja backend, também possuo conhecimento em HTML, CSS e JavaScript, o que me permite atuar em projetos full stack e colaborar de forma eficaz com equipes multidisciplinares.
</p>
<p class="text-slate-300 text-base font-normal leading-relaxed mt-4">
  Estou em constante evolução e em busca de oportunidades para atuar com backend, contribuindo com soluções eficientes e escaláveis.
</p>
</div>
</main>
</div>
</div>
</body></html>