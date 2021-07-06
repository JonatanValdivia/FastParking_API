'use strict'

const $ = (element) => document.querySelector(element);
const $$ = (element) => document.querySelectorAll(element)

const getCliente = async ( url ) => fetch ( url ).then ( res => res.json() );

const  showClients = async () =>  {
    const url = 'http://api.fastparking.com.br/clientesPreco';
    const cliente = await getCliente(url);
    console.log(cliente);
};
showClients();


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

const criarComprovante = (cliente) =>{
  const sessaoPrecos = $('#dados');
  sessaoPrecos.innerHTML = `
    <h3>Comprovante de entrada</h3>
    <hr>
    <label for="nome">Nome: ${cliente.nome}</label>
    <label for="placa">Placa: ${cliente.placa}</label>
    <label for="data">Data: ${cliente.dataEstacionado}</label>
    <label for="hora">Hora: ${cliente.hora}</label>
    <div id="acaoImpressao">
      <button>Imprimir</button>
      <button>Cancelar</button>
    </div>
  `;
  //sessaoPrecos.appendChild(div);
}

const acoesBotoes = async(click) =>{
  const botao = click.target;
  const id = click.path[2].cells[0].firstChild.data;
  if(botao.type == 'button' && botao.innerHTML == "Saída"){
    if(confirm("Deseja mesmo sair?")){  
      await deleteClient(id);
    }
  }else if(botao.type == 'button' && botao.innerHTML == "Editar"){
    await editClient(id);
  }else if(botao.type == 'button' && botao.innerHTML == "Comp."){
    const url = `http://api.fastparking.com.br/clientes/${id}`;
    const client = await getCliente(url);
    criarComprovante(client);
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

const limparInputs = () =>{
  const inputs1 = Array.from($$('.inputs input'));
  const inputs2 = Array.from($$('.precos input'));
  inputs1.forEach(input => input.value = '');
  inputs2.forEach(input => input.value = '');
  document.getElementById('nome').dataset.idcontact = 'new';
  document.getElementById('primeiraHora').disabled = false;
  document.getElementById('segundaHora').disabled = false;
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
        <button type='button'>Saída</button>
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