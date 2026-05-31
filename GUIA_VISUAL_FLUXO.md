# 🎨 Guia Visual do Fluxo de Processo

## Passo-a-Passo Visual (pode ser impresso)

---

## ⃣ PASSO 1: CRIAÇÃO DO PROCESSO

```
┌─────────────────────────────────────────────┐
│ AÇÃO: Editor registra novo processo         │
├─────────────────────────────────────────────┤
│ Dados necessários:                          │
│ • Número do processo (ex: 031/2026)        │
│ • Assunto                                   │
│ • Competição                                │
│ • Relator                                   │
│ • Enquadramento                             │
│                                              │
│ Documentos anexados:                        │
│ • Denúncia ou Petição inicial              │
│                                              │
│ SITUAÇÃO RESULTANTE:                        │
│ ✓ AGUARDANDO CITAÇÃO                        │
├─────────────────────────────────────────────┤
│ ⏱️  Duração: Indeterminada                   │
│ 👤 Responsável: Editor do Tribunal         │
└─────────────────────────────────────────────┘
```

---

## ⃣ PASSO 2: ENVIO DA CITAÇÃO

```
┌─────────────────────────────────────────────┐
│ AÇÃO: Editor envia citação                  │
├─────────────────────────────────────────────┤
│ O que acontece:                             │
│ • Documento de citação é preparado         │
│ • Denunciado é notificado oficialmente     │
│ • Comprovante é anexado ao processo       │
│                                              │
│ Documentos anexados:                        │
│ • Comprovante de notificação              │
│                                              │
│ SITUAÇÃO RESULTANTE:                        │
│ ✓ AGUARDANDO AGENDAMENTO                    │
├─────────────────────────────────────────────┤
│ ⏱️  Duração: Indeterminada                   │
│ 👤 Responsável: Editor do Tribunal         │
└─────────────────────────────────────────────┘
```

---

## ⃣ PASSO 3: INCLUSÃO EM PAUTA

```
┌─────────────────────────────────────────────┐
│ AÇÃO: Processo é adicionado a uma Pauta    │
├─────────────────────────────────────────────┤
│ O que é uma Pauta:                          │
│ • Sessão de julgamento programada          │
│ • Agrupa vários processos para julgamento  │
│ • Data, hora e local definidos             │
│                                              │
│ Dados da Pauta:                             │
│ • Número da Pauta                          │
│ • Título/Descrição                         │
│ • Data e hora do julgamento               │
│ • Local do julgamento                      │
│                                              │
│ SITUAÇÃO RESULTANTE:                        │
│ ✓ AGENDADO                                  │
├─────────────────────────────────────────────┤
│ ⏱️  Duração: Até a data da Pauta            │
│ 👤 Responsável: Editor do Tribunal         │
└─────────────────────────────────────────────┘
```

---

## ⃣ PASSO 4: JULGAMENTO

```
┌─────────────────────────────────────────────┐
│ AÇÃO: Pauta é julgada                      │
├─────────────────────────────────────────────┤
│ O que acontece:                             │
│ • Sessão de julgamento ocorre              │
│ • Decisão é tomada e anunciada             │
│ • Editor registra resultado no sistema    │
│                                              │
│ Dados registrados:                          │
│ • Data e hora exata do julgamento         │
│ • Resultado (punição, isenção, etc.)     │
│ • Documento de decisão anexado            │
│                                              │
│ SITUAÇÃO RESULTANTE:                        │
│ ✓ JULGADO - PERÍODO DE RECURSO              │
├─────────────────────────────────────────────┤
│ ⏱️  Duração: EXATAMENTE 72 HORAS            │
│ 👤 Responsável: Editor do Tribunal         │
│                                              │
│ ⚠️  CRÍTICO: Sistema inicia contagem        │
│     de 72 horas para recurso!              │
└─────────────────────────────────────────────┘
```

---

## ⃣ PASSO 5A: PERÍODO DE RECURSO (SEM RECURSO)

```
┌─────────────────────────────────────────────┐
│ AGUARDANDO: Possível recurso de partes     │
├─────────────────────────────────────────────┤
│ Prazo: 72 HORAS = 3 dias corridos          │
│                                              │
│ EXEMPLO:                                    │
│ Julgado: 31/05/2026 às 14:00              │
│ Até:     03/06/2026 às 14:00              │
│                                              │
│ O que pode acontecer:                       │
│ • ❌ Nenhum recurso interposto             │
│                                              │
│ SITUAÇÃO RESULTANTE:                        │
│ ✓ JULGADO (FINAL)                           │
│                                              │
│ 📌 Decisão é DEFINITIVA                     │
│ 📌 Resultado fica PERMANENTE               │
│ 📌 Não há mais recursos                    │
├─────────────────────────────────────────────┤
│ 👤 Responsável: Sistema (automático)       │
└─────────────────────────────────────────────┘
```

---

## ⃣ PASSO 5B: PERÍODO DE RECURSO (COM RECURSO)

```
┌─────────────────────────────────────────────┐
│ RECURSO INTERPOSTO: Dentro dos 72 horas    │
├─────────────────────────────────────────────┤
│ O que acontece:                             │
│ • Partes interpõem recurso                 │
│ • Documento de recurso é anexado          │
│ • Editor registra recurso no sistema      │
│                                              │
│ SITUAÇÃO RESULTANTE:                        │
│ ✓ RECURSO ACEITO                            │
│                                              │
│ 📌 Processo vai para novo julgamento       │
│ 📌 Retorna aos passos 3 e 4               │
│ 📌 Nova pauta será agendada               │
├─────────────────────────────────────────────┤
│ ⏱️  Próximos passos: Agendamento novo      │
│ 👤 Responsável: Editor do Tribunal         │
└─────────────────────────────────────────────┘
```

---

## ⃣ PASSO 6: ARQUIVAMENTO (OPCIONAL)

```
┌─────────────────────────────────────────────┐
│ AÇÃO: Processo é arquivado                 │
├─────────────────────────────────────────────┤
│ O que é arquivamento:                       │
│ • Processo é finalizado definitivamente    │
│ • Sai das listas ativas                    │
│ • Fica disponível para consulta histórica  │
│                                              │
│ Quando ocorre:                              │
│ • Após julgamento final (JULGADO)         │
│ • Ou após decisão de arquivamento         │
│                                              │
│ SITUAÇÃO RESULTANTE:                        │
│ ✓ ARQUIVADO                                 │
├─────────────────────────────────────────────┤
│ ⏱️  Duração: Permanente                      │
│ 👤 Responsável: Editor do Tribunal         │
│                                              │
│ 📌 Processo nunca é deletado               │
│ 📌 Sempre recuperável para consulta        │
└─────────────────────────────────────────────┘
```

---

## 📊 Fluxo em Diagrama de Barras (Timeline)

```
PROCESSO 031/2026 - Timeline Exemplo
════════════════════════════════════════════════════════════════

Passo 1: CRIAÇÃO
├─ 31/05/2026 09:00 ────────────────────────────────────────────
│  Editor registra processo "031/2026"
│  Situação: AGUARDANDO CITAÇÃO
│

Passo 2: CITAÇÃO
├─ 31/05/2026 10:00 ────────────────────────────────────────────
│  Citação é enviada ao denunciado
│  Situação: AGUARDANDO AGENDAMENTO
│

Passo 3: AGENDAMENTO
├─ 31/05/2026 16:00 ────────────────────────────────────────────
│  Processo adicionado à Pauta
│  Data da pauta: 31/05/2026 às 14:00
│  Situação: AGENDADO
│

Passo 4: JULGAMENTO
├─ 31/05/2026 14:00 ────────────────────────────────────────────
│  Pauta é julgada
│  Resultado: "Suspensão de 3 jogos"
│  Situação: JULGADO - PERÍODO DE RECURSO
│
│  ⏰ PRAZO DE RECURSO INICIA AQUI
│

Passo 5: PERÍODO DE RECURSO (72 HORAS)
├─ 31/05 14:00 ─→ 01/06 14:00 ─→ 02/06 14:00 ─→ 03/06 14:00 ─┤
│  |               |               |               |
│  24h            48h             72h            FIM
│
│  ❓ Recurso é interposto em 02/06 às 11:00
│  ✓ Situação muda para: RECURSO ACEITO
│

Passo 6: NOVO JULGAMENTO
├─ 15/06/2026 14:00 ────────────────────────────────────────────
│  Nova pauta é julgada
│  Novo resultado registrado
│  Situação: JULGADO (FINAL)
│

Passo 7: ARQUIVAMENTO
├─ 20/06/2026 ──────────────────────────────────────────────────
│  Editor marca processo como ARQUIVADO
│  Situação: ARQUIVADO
│
```

---

## 🔑 Destaques Importantes

### ⚠️ CRÍTICO: 72 HORAS

```
┌─────────────────────────────────────────┐
│  O PRAZO DE RECURSO É ESSENCIAL         │
├─────────────────────────────────────────┤
│                                          │
│  COMEÇA: Na hora exata do julgamento   │
│  DURA:   72 horas (3 dias corridos)    │
│  ENCERRA: Processo fica JULGADO        │
│                                          │
│  Se houver recurso:                     │
│  → Situação muda para RECURSO ACEITO   │
│  → Novo julgamento é agendado          │
│  → Processo recomeça fluxo             │
│                                          │
│  Se não houver recurso:                 │
│  → Situação muda para JULGADO (final)  │
│  → Resultado é DEFINITIVO              │
│                                          │
└─────────────────────────────────────────┘
```

---

## 👥 Quem Faz O Quê?

```
┌─────────────────────────────────────────────┐
│ EDITOR DO TRIBUNAL                          │
│ (com acesso ao painel administrativo)       │
├─────────────────────────────────────────────┤
│ ✓ Criar processos                          │
│ ✓ Enviar citações                          │
│ ✓ Criar e editar pautas                    │
│ ✓ Agendar processos                        │
│ ✓ Registrar julgamentos                    │
│ ✓ Processar recursos                       │
│ ✓ Arquivar processos                       │
│ ✓ Anexar documentos                        │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ PÚBLICO (SEM LOGIN)                         │
│ (no site público)                           │
├─────────────────────────────────────────────┤
│ ✓ Consultar processos por número           │
│ ✓ Ver lista de processos                   │
│ ✓ Consultar decisões                       │
│ ✓ VER PRAZO DE RECURSO                    │
│ ✓ Acessar documentos públicos              │
│ ✓ Ver pautas agendadas                     │
└─────────────────────────────────────────────┘
```

---

## 📋 Documentos em Cada Etapa

```
CRIAÇÃO
  └─ Documento de Origem
       (denúncia/petição inicial)

CITAÇÃO
  └─ Comprovante de Notificação
       (comprovante de envio)

JULGAMENTO
  └─ Decisão/Resultado
       (resultado do julgamento)

RECURSO
  └─ Petição de Recurso
       (documento de recurso)
  └─ Parecer sobre Recurso
       (análise do recurso)

ARQUIVO
  └─ (mantém todos os anteriores)
```

---

## ✅ Checklist Visual

### Antes de colocar em Produção:

```
☐ Situações correspondem ao procedimento do Tribunal
☐ Prazo de 72 horas está correto
☐ Documentos esperados estão corretos
☐ Fluxo de recurso está alinhado
☐ Acesso público é adequado
☐ Não há gaps no fluxo
☐ Prazos críticos estão respeitados
☐ Sistema calcula corretamente as 72 horas
☐ Transições de situação são automáticas quando necessário
☐ Auditoria de ações está funcionando
```

---

## 📞 Resumo Rápido

**Qual é o fluxo?**  
Criação → Citação → Agendamento → Julgamento → (Recurso ou Julgado Final) → Arquivamento

**Qual é o prazo crítico?**  
72 horas após julgamento para interpor recurso

**Quem acessa o quê?**  
Editor: tudo  |  Público: consulta e decisões

**É seguro?**  
Sim: autenticação, validação, auditoria completa

**Pronto para Produção?**  
✅ SIM - após validação com o Tribunal

---

**Este guia é uma versão simplificada. Para detalhes técnicos, consulte FLUXO_PROCESSO_TRIBUNAL.md**

Versão 1.0 — 31 de maio de 2026
