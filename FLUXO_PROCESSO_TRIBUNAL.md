# Fluxo de Processo - Sistema de Julgamento do Tribunal

## Resumo Executivo

Este documento descreve o fluxo operacional completo de um processo no sistema de gerenciamento de julgamentos do Tribunal, desde sua criação até a finalização com a situação "Julgado". O sistema automatiza o acompanhamento de todas as etapas e transições, garantindo conformidade com os prazos e procedimentos estabelecidos.

---

## Diagrama do Fluxo

```
┌─────────────────────────────────────────────────────────────────────┐
│                                                                       │
│  1. CRIAÇÃO DO PROCESSO                                             │
│     ↓                                                                │
│  2. AGUARDANDO CITAÇÃO                                             │
│     ↓ (citação enviada)                                            │
│  3. AGUARDANDO AGENDAMENTO                                         │
│     ↓ (incluído em pauta)                                          │
│  4. AGENDADO                                                        │
│     ↓ (pauta julgada)                                              │
│  5. JULGADO - PERÍODO DE RECURSO (72 horas/3 dias corridos)        │
│     ↓ (prazo de recurso encerrado)                                 │
│  6. JULGADO OU RECURSO ACEITO                                      │
│     ↓                                                               │
│  7. ARQUIVADO (opcional)                                            │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Etapas Detalhadas do Fluxo

### **ETAPA 1: Criação do Processo**

**O que acontece:**
- Um novo processo é registrado no sistema com informações básicas
- Documentos de origem são anexados (denúncia, petição, etc.)

**Dados obrigatórios:**
- Número do processo (identificador único - ex.: 031/2026)
- Assunto (descrição do caso)
- Competição (ex.: Campeonato Brasileiro)
- Relator (responsável pelo julgamento)
- Enquadramento (artigo ou norma infringida)

**Dados opcionais:**
- Denunciante (quem fez a denúncia)
- Denunciado (clube ou pessoa denunciada)
- Partida (identificação da partida envolvida)
- Clube (clube afetado)

**Documentos anexados:**
- Documento de origem (denúncia, petição inicial)

**Situação resultante:** `Aguardando Citação`

**Responsável:** Editor do Sistema (Tribunal)

---

### **ETAPA 2: Aguardando Citação**

**Duração:** Indeterminada (até que a citação seja enviada)

**O que acontece:**
- O processo fica aguardando o envio da citação ao acusado/denunciado
- Nenhuma ação automática ocorre durante este período

**Documentos esperados:**
- Citação do procurador (comprovante de envio da notificação)

**Como avançar para próxima etapa:**
- Editor carrega o documento de citação no sistema

**Situação resultante:** `Aguardando Agendamento`

**Responsável:** Editor do Sistema (Tribunal)

---

### **ETAPA 3: Aguardando Agendamento**

**Duração:** Indeterminada (até que seja incluído em pauta)

**O que acontece:**
- O processo aguarda ser incluído em uma Pauta de julgamento
- A Pauta é um evento agendado que agrupa vários processos a serem julgados simultaneamente

**Informações da Pauta:**
- Número da Pauta
- Título/Descrição
- Órgão Julgador (Comissão, Tribunal, etc.)
- Data e hora do julgamento
- Local do julgamento

**Como avançar para próxima etapa:**
- Editor cria ou edita uma Pauta
- Adiciona este processo à Pauta (Pauta "agendada")

**Situação resultante:** `Agendado`

**Responsável:** Editor do Sistema (Tribunal)

---

### **ETAPA 4: Agendado**

**Duração:** Até a data e hora da Pauta

**O que acontece:**
- Processo está incluído em uma Pauta com data/hora definida
- Aviso público é divulgado da agenda de julgamento
- Todas as partes envolvidas têm conhecimento da data/hora do julgamento

**Informações públicas:**
- Processo aparece na página "Decisões" e "Processos" do site público
- Citação está disponível para consulta pública
- Documentos relacionados estão acessíveis

**Documentos possíveis:**
- Citação do procurador
- Documentos da Pauta (relatórios, pareceres)

**Como avançar para próxima etapa:**
- Editor marca a Pauta como "Julgada" após o julgamento ocorrer

**Situação resultante:** `Julgado - Período de Recurso`

**Responsável:** Editor do Sistema (Tribunal)

---

### **ETAPA 5: Julgado - Período de Recurso**

**Duração:** 72 horas (3 dias corridos) a partir do julgamento

**O que acontece:**
- A Pauta é marcada como "Julgada" no sistema
- Processo recebe resultado do julgamento e passa para período de recurso
- Sistema calcula automaticamente o prazo de recurso: **72 horas corridas**
- O resultado (punição, isenção, etc.) é registrado

**Dados registrados:**
- Data e hora exata do julgamento
- Resultado do julgamento (punição, advertência, arquivamento, etc.)
- Documentos de decisão podem ser anexados

**Acesso público:**
- Processo aparece em "Decisões" com prazo de recurso visível
- Permite consulta dos autos do julgamento
- Resultado fica público imediatamente

**Como calcular o prazo de recurso:**
- **Início:** Data/hora exata da Pauta julgada
- **Término:** 72 horas após o julgamento
- **Exemplo:** Se julgado em 31/05/2026 às 14:00, prazo encerra em 03/06/2026 às 14:00

**O que pode acontecer durante este período:**
1. Nenhuma ação → Processo segue para "Julgado" automaticamente após 72 horas
2. Recurso interposto → Processo vai para "Recurso Aceito" (se o recurso for válido)

**Situação resultante:** 
- `Julgado` (se prazo encerrar sem recurso)
- `Recurso Aceito` (se houver recurso válido)

**Responsável:** Sistema (automático) ou Editor (se houver recurso)

---

### **ETAPA 6: Julgado ou Recurso Aceito**

**Cenário A: Julgado (Final)**

**O que significa:**
- Prazo de recurso de 72 horas encerrou
- Nenhum recurso válido foi interposto
- Decisão é definitiva

**Acesso público:**
- Processo aparece em "Decisões" como "Julgado"
- Aparece em "Punidos" (se houver resultado/punição)
- Resultado fica permanentemente registrado

**Documentos possíveis:**
- Decisão final
- Documentos de recurso rejeitados

---

**Cenário B: Recurso Aceito**

**O que significa:**
- Um recurso válido foi interposto durante o período de recurso
- Recurso foi analisado e aceito
- Processo retorna para nova análise/julgamento

**Acesso público:**
- Processo aparece em "Decisões" como "Recurso Aceito"
- Indica que haverá novo julgamento
- Documentos do recurso ficam públicos

**Próximos passos:**
- Processo entra em novo ciclo de julgamento
- Pode ser reagendado em nova Pauta
- Segue novamente as etapas de agendamento até novo julgamento

**Documentos esperados:**
- Documento/Petição de recurso
- Parecer sobre o recurso

**Responsável:** Editor do Sistema (Tribunal)

---

### **ETAPA 7: Arquivado (Opcional)**

**O que significa:**
- Processo é finalmente encerrado
- Não mais aparece nas listas ativas
- Fica disponível para consulta histórica

**Quando ocorre:**
- Após julgamento final (sem recursos pendentes)
- Quando há decisão de arquivamento do processo

**Acesso público:**
- Mantém-se acessível para consulta histórica
- Pode ser recuperado para análise futura se necessário

**Responsável:** Editor do Sistema (Tribunal)

---

## Documentos no Sistema

### **Tipos de Documentos**

| Tipo | Descrição | Quando | Responsável |
|------|-----------|--------|-------------|
| **Origem** | Denúncia ou petição inicial | Criação do processo | Editor |
| **Citação** | Comprovante de notificação ao acusado | Etapa 2 → 3 | Editor |
| **Decisão** | Resultado do julgamento | Após pauta julgada | Sistema/Editor |
| **Recurso** | Petição de recurso | Durante prazo de recurso | Partes |
| **Decisão Recurso** | Parecer sobre o recurso | Análise do recurso | Editor |

### **Fluxo de Documentos**

```
Processo Criado
    ├── Documento: Origem (denúncia/petição)
    │
Citação Enviada
    ├── Documento: Citação (comprovante)
    │
Pauta Julgada
    ├── Documento: Decisão (resultado)
    │
Período de Recurso (72h)
    ├── Documento: Recurso (se interposto)
    └── Documento: Decisão Recurso (parecer)
```

---

## Prazos Críticos

| Evento | Prazo | Tipo | Observações |
|--------|-------|------|-------------|
| Envio de Citação | Não definido | Administrativo | Deve ocorrer antes do julgamento |
| Agendamento | Não definido | Administrativo | Agendado conforme calendário |
| Recurso | 72 horas | Legal | 3 dias corridos após o julgamento |
| Análise de Recurso | Não definido | Administrativo | Conforme calendário |

---

## Transições de Situação (Resumo)

```
aguardando_citacao
        ↓ (citação enviada)
aguardando_agendamento
        ↓ (incluído em pauta)
agendado
        ↓ (pauta julgada)
julgado_periodo_recurso
        ├─→ julgado (prazo encerrado sem recurso)
        └─→ recurso_aceito (recurso interposto e aceito)
                ├─→ agendado (retorna para novo julgamento)
                └─→ julgado (novo julgamento final)

julgado (ou recurso_aceito)
        ↓ (opcional)
arquivado
```

---

## Responsabilidades e Acessos

### **Editor do Tribunal**
- ✅ Criar novo processo
- ✅ Anexar documentos de origem
- ✅ Enviar citação (anexar documento)
- ✅ Criar e editar Pautas
- ✅ Adicionar processos a Pautas
- ✅ Marcar Pauta como julgada
- ✅ Registrar resultado do julgamento
- ✅ Processar recursos
- ✅ Arquivar processos
- ✅ Visualizar toda informação

### **Público (Acesso sem autenticação)**
- 🔍 Consultar processo por número
- 🔍 Visualizar lista de processos
- 🔍 Consultar decisões e prazos de recurso
- 🔍 Acessar documentos públicos (citação, decisão)
- 🔍 Visualizar pautas agendadas

---

## Garantias do Sistema

### **Automatismos**
- ✅ Cálculo automático de prazo de recurso (72 horas)
- ✅ Identificação de processos com recurso em prazo
- ✅ Histórico completo de andamentos e documentos
- ✅ Rastreabilidade de quem fez cada ação e quando

### **Segurança**
- ✅ Validação de número único de processo
- ✅ Autenticação obrigatória para edição
- ✅ Autorização por tipo de usuário (editor)
- ✅ Armazenamento seguro de arquivos
- ✅ Backup de todas as alterações

### **Conformidade**
- ✅ Respeita prazos legais (72 horas para recurso)
- ✅ Mantém auditoria completa de ações
- ✅ Preserva integridade de documentos
- ✅ Disponibiliza informações publicamente conforme necessário

---

## Exemplo Prático: Julgamento de um Processo Real

### **Dia 1: Criação**
```
- Editor cria processo "031/2026" 
- Anexa denúncia como documento de origem
- Situação: AGUARDANDO CITAÇÃO
```

### **Dia 2: Citação**
```
- Editor envia citação ao denunciado
- Anexa comprovante de notificação
- Situação: AGUARDANDO AGENDAMENTO
```

### **Dia 10: Agendamento**
```
- Editor cria Pauta "2026/05 - Sessão Extraordinária"
- Data: 31/05/2026, 14:00, Sala de Julgamentos
- Adiciona processo 031/2026 à pauta
- Situação: AGENDADO
```

### **Dia 31/05 às 14:00: Julgamento**
```
- Pauta é marcada como JULGADA
- Resultado: "Suspensão de 3 jogos"
- Sistema calcula prazo de recurso: até 03/06/2026 às 14:00
- Situação: JULGADO - PERÍODO DE RECURSO
```

### **Dia 01/06: Recurso Interposto**
```
- Denunciado interpõe recurso
- Editor anexa petição de recurso
- Editor marca como "Recurso Aceito"
- Situação: RECURSO ACEITO
```

### **Dia 15/06: Novo Julgamento**
```
- Processo é reagendado em nova pauta
- Nova data: 15/06/2026
- Pauta é julgada, novo resultado é registrado
- Situação: JULGADO (Final)
```

### **Dia 20/06: Arquivamento**
```
- Editor marca processo como ARQUIVADO
- Processo fica inacessível nas listas ativas
- Mantém-se disponível para consulta histórica
```

---

## Checklist para Validação com o Tribunal

- [ ] Etapas do fluxo correspondem ao procedimento real do Tribunal
- [ ] Prazos estão corretos (especialmente os 72 horas de recurso)
- [ ] Documentos esperados em cada etapa estão identificados
- [ ] Responsabilidades e acessos estão claramente definidos
- [ ] Transições entre situações fazem sentido processual
- [ ] Não há gaps no fluxo entre criação e julgado
- [ ] Funcionalidades de arquivamento e recurso estão alinhadas
- [ ] Prazos críticos estão respeitados pelo sistema

---

## Próximos Passos

1. **Validação:** Apresentar este fluxo para a equipe do Tribunal
2. **Ajustes:** Incorporar feedbacks e correções conforme necessário
3. **Testes:** Realizar testes em ambiente de staging com casos reais
4. **Produção:** Deploy após validação e testes bem-sucedidos

---

**Versão:** 1.0  
**Data:** 31 de maio de 2026  
**Autor:** Sistema de Gestão de Processos do Tribunal
