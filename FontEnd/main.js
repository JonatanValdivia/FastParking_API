'use strict'

const $ = (element) => document.querySelector(element);
const $$ = (element) => document.querySelectorAll(element)

const getCliente = async ( url ) => fetch ( url ).then ( res => res.json() );

const  showClients = async () =>  {
    const url = 'http://api.fastparking.com.br/clientesPreco';
    const cliente = await getCliente(url);
};


const createPreco = async(preco) =>{
  const url = 'http://api.fastparking.com.br/precos';
  const options = {
    method: 'POST',
    body: JSON.stringify(preco)
  }
  await fetch(url, options);
}

const createClient = async(newClient) =>{
  const url = 'http://api.fastparking.com.br/clientes';
  const options = {
    method: 'POST',
    body: JSON.stringify(newClient)
  }
  await fetch(url, options);
}

const deleteClient = async(idClient) =>{
  const url = `http://api.fastparking.com.br/clientes/${idClient}`;
  const options = {
    method: 'DELETE',
    body: JSON.stringify(idClient)
  }
  await fetch(url, options);
  updateTable();
}

const updateClint = async(idClient) =>{
  const url = `http://api.fastparking.com.br/clientes/${idClient.id}`;
  const options = {
    method: 'PUT',
    body: JSON.stringify(idClient)
  }
  await fetch(url, options);
}

const editClient = async(idClient) =>{
  const url = `http://api.fastparking.com.br/clientes/${idClient}`;
  const client = await getCliente(url);
  $('#nome').value = client.nome;
  $('#placa').value = client.placa;
  document.getElementById('nome').dataset.idcontact = client.id
  document.getElementById('primeiraHora').disabled = true;
    document.getElementById('segundaHora').disabled = true;
}

const acoesBotoes = async(click) =>{
  const botao = click.target;
  if(botao.type == 'button' && botao.innerHTML == "Saída"){
    const id = click.path[2].cells[0].firstChild.data;
    await deleteClient(id);
    updateTable();
  }else if(botao.type == 'button' && botao.innerHTML == "Editar"){
    const id = click.path[2].cells[0].firstChild.data;
    await editClient(id);
  }
}

function animacoes(){
  const sessaoPrecos = $('.sessaoPrecos');
  sessaoPrecos.style.animation = 'go-back 1s';
}

const criarTabelaPrecos = () =>{
  const sessaoTabelaPrecos = $('.precos').classList.remove('none') 
  animacoes();
}

const fecharTabelaPrecos = () =>{
  const sessaoTabelaPrecos = $('.precos').classList.add('none') 
}

const lerBancoDeDados = () => JSON.parse(localStorage.getItem('db')) ?? [];

const setarBancoDeDados = (db) => localStorage.setItem('db', JSON.stringify(db))

const criarComprovante = (cliente) =>{
  const div = document.createElement('div');
  const sessaoPrecos = $('#sessaoComprovante');
  div.innerHTML = `
    <h3>Comprovante</h3>
    <hr>
    <div id="dados">
      <label for="nome">Nome: ${cliente.nome}</label>
      <label for="placa">Placa: ${cliente.placa}</label>
      <label for="data">Data: ${cliente.data}</label>
      <label for="hora">Hora: ${cliente.hora}</label>
    </div>
    <div id="acaoImpressao">
      <button>Imprimir</button>
      <button>Cancelar</button>
    </div>
  `;

  sessaoPrecos.appendChild(div);
}

const limparInputs = () =>{
  const inputs1 = Array.from($$('.inputs input'));
  const inputs2 = Array.from($$('.precos input'));
  inputs1.forEach(input => input.value = '');
  inputs2.forEach(input => input.value = '');
  document.getElementById('nome').dataset.idcontact = 'new';
  document.getElementById('primeiraHora').disabled = false;
  document.getElementById('segundaHora').disabled = false;
}

const data = () =>{
  let data = new Date();
  let dia = data.getDate();
  let mes = data.getMonth()+1;
  let ano = data.getFullYear();

  if(dia.toString().length == 1) dia = '0'+dia;
  if(mes.toString().length == 1) mes = '0'+mes;

  return `${dia}/${mes}/${ano}`
  
}

const horaSaida = (primeiraHora, demaisHoras) =>{
  const segundosUmaHora = 60 * 60;
  let hora = segundosUmaHora / 3600;
  let primeiraHoraCliente = primeiraHora * hora;
  let horaSaida = new Date().getHours() + primeiraHoraCliente;
  let minutos = new Date().getMinutes();
  return horaSaida + ":" + minutos;

}

const horaEntrada = () =>{
  let hora = new Date().getHours();
  let minutos = new Date().getMinutes();
  return hora + ":" + minutos;
}

const formPreco = () =>{
  const horaCliente ={
    primeiraHora: $('#primeiraHora').value,
    demaisHoras: $('#demaisHoras').value
  }
  return horaSaida(horaCliente.primeiraHora, horaCliente.demaisHoras)  
} 

const criarNovaLinha = (cliente, indice) => {

  const linhaClienteCadastrado = document.createElement('tr')
    const tbody = $('#cadastros #tbody')
    linhaClienteCadastrado.innerHTML = `
      <td>${cliente.id}</td>    
      <td>${cliente.nome}</td>
      <td>${cliente.placa}</td>
      <td>${cliente.dataEstacionado}</td>
      <td>${cliente.hora}</td>
      <td>
        <button type='button' id="telaComprovante" data-acao="comprovante-${indice}">Comp.</button>
        <button type='button' data-acao="editar-${indice}">Editar</button>
        <button type='button' id="deletar">Saída</button>
      </td>
    `
    tbody.appendChild(linhaClienteCadastrado);
}

const limparTela = () =>{
  const tbody = document.getElementById('tbody')
  while(tbody.firstChild){
    tbody.removeChild(tbody.lastChild);
  }
}

const updateTable = async () => {
  limparTela();
  const url = "http://api.fastparking.com.br/clientesPreco";
  const clientes = await getCliente(url);
  clientes.forEach(criarNovaLinha)
}

const mascaraPlaca = (evento) => {
  var cleave = new Cleave('#placa', {
    delimiter: '-',
    blocks: [3, 4],
    uppercase: true
});
}

const validarCampos = () => {
  if($('#placa').reportValidity() && $('#nome').reportValidity()){
    return true;
  }
}

const adicionarCliente = async() => {
  const idContact = document.getElementById('nome').dataset.idcontact
  if(idContact == 'new'){
    if($('#primeiraHora').value == '' && $('#segundaHora').value == ''){
      alert('Preencha os campos de preços');
    }else{
      if(validarCampos()){
        const dadosPreco = {
          "primeiraHora": $('#primeiraHora').value,
          "segundaHora": $('#segundaHora').value
        }

        const dadosCliente = {
          "nome": $('#nome').value,
          "placa": $('#placa').value,
          "primeiraHora": $('#primeiraHora').value,
          "segundaHora": $('#segundaHora').value
        }
        await createPreco(dadosPreco);
        await createClient(dadosCliente);
        updateTable();
        limparInputs();
      }
    }
  }else{
    const dadosClienteUp = {
      "nome": $('#nome').value,
      "placa": $('#placa').value
    }
    dadosClienteUp.id = idContact
    console.log(dadosClienteUp)
    await updateClint(dadosClienteUp);
    
    updateTable();
    limparInputs();
  }
} 

$('#buttonAdicionar').addEventListener('click', adicionarCliente);
$('#buttonPreco').addEventListener('click', criarTabelaPrecos)
$('#buttonCancelar').addEventListener('click', () => {fecharTabelaPrecos(); limparInputs();})
$('#placa').addEventListener('keyup', mascaraPlaca)
$('tbody').addEventListener('click', acoesBotoes);
$('#salvarPrecos').addEventListener('click', () => {
  adicionarCliente();
})

updateTable()