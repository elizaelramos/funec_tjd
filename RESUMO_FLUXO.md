# Resumo Executivo - Fluxo de Processo do Tribunal

## Visão Geral

O sistema gerencia o ciclo de vida completo de um processo, desde a criação até a finalização (julgado). Cada etapa é automatizada e rastreável.

---

## As 7 Situações de um Processo

```
1️⃣  AGUARDANDO CITAÇÃO
    Processo registrado, aguardando envio de citação
    ⏱️ Duração: Indeterminada

2️⃣  AGUARDANDO AGENDAMENTO
    Citação enviada, aguardando inclusão em pauta
    ⏱️ Duração: Indeterminada

3️⃣  AGENDADO
    Incluído em pauta com data/hora definida
    ⏱️ Duração: Até a data da pauta

4️⃣  JULGADO - PERÍODO DE RECURSO
    Pauta julgada, abrindo prazo de 72 horas para recurso
    ⏱️ Duração: 72 horas (3 dias corridos)

5️⃣  JULGADO
    Prazo de recurso encerrado, decisão final
    ⏱️ Duração: Permanente

6️⃣  RECURSO ACEITO
    Recurso válido foi interposto durante o prazo
    ⏱️ Ação: Reagendar para novo julgamento

7️⃣  ARQUIVADO
    Processo finalizado e arquivado
    ⏱️ Duração: Permanente
```

---

## Fluxo Principal (Caminho Feliz)

```
┌──────────────────┐
│ 1. CRIAÇÃO       │ ← Editor registra processo
└────────┬─────────┘
         │
         ↓
┌──────────────────────────┐
│ 2. AGUARDANDO CITAÇÃO    │ ← Citação sendo preparada
└────────┬─────────────────┘
         │ citação enviada
         ↓
┌──────────────────────────┐
│ 3. AGUARDANDO AGENDAM.   │ ← Processo aguarda pauta
└────────┬─────────────────┘
         │ processo adicionado a pauta
         ↓
┌──────────────────────────┐
│ 4. AGENDADO              │ ← Data/hora do julgamento confirmada
└────────┬─────────────────┘
         │ pauta julgada
         ↓
┌──────────────────────────┐
│ 5. PERÍODO DE RECURSO    │ ← 72 HORAS para recurso
│    (Julgado em período)  │
└────────┬─────────────────┘
         │ prazo encerrado
         ↓
┌──────────────────────────┐
│ 6. JULGADO (FINAL)       │ ← Decisão é definitiva
└────────┬─────────────────┘
         │ opcional
         ↓
┌──────────────────────────┐
│ 7. ARQUIVADO             │ ← Processo finalizado
└──────────────────────────┘
```

---

## Documentos Obrigatórios em Cada Etapa

| Etapa | Documento | Quem fornece |
|-------|-----------|-------------|
| Criação | Denúncia / Origem | Tribunal |
| Citação | Comprovante de notificação | Tribunal |
| Pauta Julgada | Decisão / Resultado | Tribunal |
| Período de Recurso | Petição de Recurso (se houver) | Partes |

---

## O Prazo de Recurso: 72 HORAS

### Como funciona:
- **Início:** Momento exato do julgamento da pauta
- **Duração:** 72 horas corridas (= 3 dias corridos)
- **Fim:** Prazo encerrado = Processo fica automaticamente "Julgado"

### Exemplo:
```
Julgamento: 31/05/2026 às 14h
Prazo até:  03/06/2026 às 14h (72 horas depois)

Se recurso for interposto:
  → Situação muda para "Recurso Aceito"
  → Processo reagenda para novo julgamento

Se nenhum recurso:
  → Situação passa para "Julgado" (final)
  → Resultado fica permanentemente registrado
```

---

## Acessos no Sistema

### 👨‍💼 Editor (Tribunal)
- Criar processos
- Enviar citações
- Criar/editar pautas
- Marcar pautas como julgadas
- Registrar resultados
- Processar recursos
- Anexar documentos
- **Accesso total ao sistema**

### 👥 Público (sem login)
- Consultar processos por número
- Ver lista de processos
- Consultar decisões
- **Ver prazo de recurso**
- Acessar documentos públicos

---

## O Que o Sistema Garante

✅ **Automatismo:**
- Cálculo automático de 72 horas de recurso
- Transições automáticas entre situações
- Rastreamento de prazos

✅ **Transparência:**
- Histórico completo de andamentos
- Auditoria de quem fez cada ação
- Datas e horários exatos

✅ **Conformidade:**
- Respeita prazos legais
- Preserva integridade de documentos
- Mantém backup automático

✅ **Segurança:**
- Validação de dados
- Autenticação obrigatória
- Autorização por tipo de usuário

---

## Checklist de Validação

Antes de ir para produção, validar com Tribunal:

- [ ] Situações correspondem ao procedimento real
- [ ] Prazo de 72 horas está correto
- [ ] Documentos esperados estão corretos
- [ ] Fluxo de recurso está alinhado
- [ ] Acesso público é o adequado
- [ ] Não há gaps no fluxo
- [ ] Prazos críticos estão respeitados

---

## Status: Pronto para Apresentação ✅

Este documento está pronto para ser apresentado à equipe do Tribunal para validação antes dos testes em produção.

**Tempo estimado de apresentação:** 15-20 minutos

**Documentos de suporte:**
- `FLUXO_PROCESSO_TRIBUNAL.md` - Versão detalhada completa
- Este resumo - Para apresentação rápida
