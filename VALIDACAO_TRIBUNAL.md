# Validação do Fluxo com a Equipe do Tribunal

## Objetivo
Documento para apresentação e coleta de feedback da equipe do Tribunal antes de migrar para produção.

---

## Seção 1: Confirmação das Situações/Estados

### Pergunta 1.1
**O processo passa por essas 7 situações nesta ordem?**

```
1. Aguardando Citação
2. Aguardando Agendamento
3. Agendado
4. Julgado - Período de Recurso (72 horas)
5. Julgado (Final) OU Recurso Aceito
6. Arquivado (opcional)
```

**Resposta do Tribunal:** ☐ SIM  ☐ NÃO  ☐ PRECISA AJUSTE

**Observações/Ajustes necessários:**
```
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

---

## Seção 2: Validação do Prazo de Recurso

### Pergunta 2.1
**O prazo para recurso é realmente 72 horas (3 dias corridos) contados a partir do momento exato do julgamento?**

**Resposta do Tribunal:** ☐ SIM  ☐ NÃO  ☐ PRECISA AJUSTE

**Se não, qual é o prazo correto?** 
```
_________________________________________________________________
```

### Pergunta 2.2
**O sistema deve calcular esse prazo automaticamente e avisar as partes quando está próximo de encerrar?**

**Resposta do Tribunal:** ☐ SIM  ☐ NÃO  ☐ TALVEZ

**Observações:**
```
_________________________________________________________________

_________________________________________________________________
```

---

## Seção 3: Documentos por Etapa

### Pergunta 3.1
**Estes são os documentos esperados em cada etapa?**

| Etapa | Documento Esperado | ✓ Correto | ✗ Ajuste |
|-------|-------------------|----------|----------|
| Criação | Denúncia / Origem | ☐ | ☐ |
| Citação | Comprovante de notificação | ☐ | ☐ |
| Pauta Julgada | Decisão / Resultado | ☐ | ☐ |
| Período de Recurso | Petição de recurso (se houver) | ☐ | ☐ |

**Documentos faltando ou incorretos:**
```
_________________________________________________________________

_________________________________________________________________
```

---

## Seção 4: Acesso e Publicidade

### Pergunta 4.1
**O que deve ser visível ao público (sem fazer login)?**

Atualmente visível:
- ✓ Processos em lista geral (filtráveis)
- ✓ Detalhe de cada processo
- ✓ Documentos de origem e citação
- ✓ Decisões e prazos de recurso
- ✓ Data/hora das pautas

**Isso está correto?** ☐ SIM  ☐ NÃO  ☐ PRECISA AJUSTE

**O que deveria ser privado ou oculto?**
```
_________________________________________________________________

_________________________________________________________________
```

### Pergunta 4.2
**O que só o editor/tribunal pode fazer?**

Atualmente:
- ✓ Criar novo processo
- ✓ Enviar citações
- ✓ Criar e editar pautas
- ✓ Marcar pauta como julgada
- ✓ Registrar resultado
- ✓ Processar recursos
- ✓ Arquivar processo

**Isso está completo e correto?** ☐ SIM  ☐ NÃO  ☐ PRECISA AJUSTE

**Ações faltando:**
```
_________________________________________________________________

_________________________________________________________________
```

---

## Seção 5: Fluxo de Recurso

### Pergunta 5.1
**Quando um recurso é interposto durante o período de 72 horas:**

**O processo deve:**
- ☐ Mudar para "Recurso Aceito"
- ☐ Ser reagendado em nova pauta
- ☐ Passar por novo julgamento
- ☐ Isso está correto?

**Observações:**
```
_________________________________________________________________

_________________________________________________________________
```

### Pergunta 5.2
**E se não houver recurso nos 72 horas?**

**O processo deve:**
- ☐ Mudar automaticamente para "Julgado" (final)
- ☐ Resultado fica permanentemente registrado
- ☐ Isso está correto?

**Observações:**
```
_________________________________________________________________

_________________________________________________________________
```

---

## Seção 6: Casos Especiais

### Pergunta 6.1
**O processo pode voltar a estados anteriores? (ex: de "Agendado" para "Aguardando Agendamento")**

**Resposta do Tribunal:** ☐ SIM  ☐ NÃO

**Em que casos?**
```
_________________________________________________________________

_________________________________________________________________
```

### Pergunta 6.2
**O processo pode ser excluído depois de criado?**

**Resposta do Tribunal:** ☐ SIM  ☐ NÃO

**Em que circunstâncias?**
```
_________________________________________________________________

_________________________________________________________________
```

### Pergunta 6.3
**O que causa arquivamento de um processo?**

- ☐ Apenas decisão manual do editor
- ☐ Automaticamente após prazo de recurso sem recurso
- ☐ Quando houver decisão de arquivamento no resultado
- ☐ Outra: _________________________________

---

## Seção 7: Dados e Campos

### Pergunta 7.1
**Estes campos são obrigatórios na criação de um processo?**

| Campo | Obrigatório | Opcional |
|-------|------------|----------|
| Número | ☐ | ☐ |
| Assunto | ☐ | ☐ |
| Competição | ☐ | ☐ |
| Relator | ☐ | ☐ |
| Enquadramento | ☐ | ☐ |
| Denunciante | ☐ | ☐ |
| Denunciado | ☐ | ☐ |
| Clube | ☐ | ☐ |
| Partida | ☐ | ☐ |
| Resultado | ☐ | ☐ |

**Campos faltando ou redundantes:**
```
_________________________________________________________________

_________________________________________________________________
```

---

## Seção 8: Fluxo de Exemplo

### Pergunta 8.1
**Validar este exemplo de fluxo real:**

```
31/05/2026 09:00 - Editor cria processo "031/2026"
                  - Anexa denúncia como origem
                  - Situação: AGUARDANDO CITAÇÃO

31/05/2026 10:00 - Editor envia citação
                  - Anexa comprovante de notificação
                  - Situação: AGUARDANDO AGENDAMENTO

31/05/2026 16:00 - Editor cria Pauta "2026/05 - Sessão"
                  - Data: 31/05/2026 às 14:00
                  - Adiciona processo 031/2026
                  - Situação: AGENDADO

31/05/2026 14:00 - Pauta é julgada
                  - Resultado: "Suspensão de 3 jogos"
                  - Situação: JULGADO - PERÍODO DE RECURSO
                  - Prazo até: 03/06/2026 às 14:00

02/06/2026 11:00 - Recurso é interposto
                  - Editor anexa petição
                  - Situação: RECURSO ACEITO

15/06/2026 14:00 - Nova pauta julgada
                  - Novo resultado registrado
                  - Situação: JULGADO (final)

20/06/2026       - Editor marca como arquivado
                  - Situação: ARQUIVADO
```

**Este fluxo está correto?** ☐ SIM  ☐ NÃO  ☐ PRECISA AJUSTE

**Problemas ou incoerências:**
```
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

---

## Seção 9: Prazos e Notificações

### Pergunta 9.1
**Quem deve ser notificado em cada transição de situação?**

| Evento | Notificar | Como | Quando |
|--------|-----------|------|--------|
| Processo criado | ☐ Público | ☐ Email/☐ Sistema | Imediato |
| Citação enviada | ☐ Público | ☐ Email/☐ Sistema | Imediato |
| Processo agendado | ☐ Público | ☐ Email/☐ Sistema | Imediato |
| Pauta julgada | ☐ Público | ☐ Email/☐ Sistema | Imediato |
| Prazo de recurso terminando | ☐ Público | ☐ Email/☐ Sistema | 24h antes? |

**Ajustes necessários:**
```
_________________________________________________________________

_________________________________________________________________
```

---

## Seção 10: Relatórios e Estatísticas

### Pergunta 10.1
**Que relatórios o Tribunal precisa gerar?**

- ☐ Processos por situação (aguardando citação, agendados, julgados, etc.)
- ☐ Processos em período de recurso (com prazo)
- ☐ Recursos aceitos pendentes de novo julgamento
- ☐ Processos arquivados por período
- ☐ Tempo médio em cada etapa
- ☐ Outros: _________________________________

**Prioridade para v1:** (listar)
```
_________________________________________________________________

_________________________________________________________________
```

---

## Seção 11: Questões Técnicas

### Pergunta 11.1
**Qual é o horário comercial do Tribunal para julgamentos?**

```
_________________________________________________________________
```

**Por que é importante?** Sistema pode ajustar prazo de recursos (72 horas pode não contar fins de semana)

### Pergunta 11.2
**Feriados e fins de semana contam nos 72 horas de recurso?**

**Resposta do Tribunal:** ☐ SIM (contam)  ☐ NÃO (não contam)

---

## Seção 12: Feedback Geral

### Pergunta 12.1
**Existem erros ou discrepâncias na documentação do fluxo apresentada?**

```
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

### Pergunta 12.2
**O que está funcionando bem?**

```
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

### Pergunta 12.3
**O que precisa ser melhorado ou corrigido?**

```
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

---

## Resumo de Ajustes Necessários

Com base no feedback do Tribunal, os seguintes ajustes são necessários:

1. **CRÍTICO (bloqueia produção):**
   ```
   _________________________________________________________________
   ```

2. **IMPORTANTE (deve ser feito antes de produção):**
   ```
   _________________________________________________________________
   ```

3. **NICE-TO-HAVE (pode ficar para v1.1):**
   ```
   _________________________________________________________________
   ```

---

## Próximas Etapas

- [ ] Apresentação do fluxo para equipe do Tribunal
- [ ] Coleta de respostas neste formulário
- [ ] Análise de ajustes necessários
- [ ] Implementação de correções
- [ ] **Testes em staging com casos reais**
- [ ] **Aprovação final do Tribunal**
- [ ] Deploy em produção

---

## Assinaturas de Validação

**Apresentado por:** _________________________________  
**Data:** _________________________________

**Validado por:** _________________________________  
**Cargo:** _________________________________  
**Data:** _________________________________

**Notas finais:**
```
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

---

**Versão:** 1.0  
**Data de criação:** 31 de maio de 2026
