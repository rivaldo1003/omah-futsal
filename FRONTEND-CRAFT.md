# Frontend Craft — AI Slop Guard

This project uses the [frontend-craft](https://github.com/nattergabriel/frontend-craft) agent skill to keep the UI from drifting into generic, AI-generated design ("slop").

## What was installed

The skill was copied into the two standard agent skill directories:

- `.claude/skills/frontend-craft/` (Claude Code)
- `.agents/skills/frontend-craft/` (Codex and other `.agents` harnesses)

Each contains `SKILL.md` plus three references:

| File | Purpose |
| :--- | :--- |
| `SKILL.md` | Workflow, rules by dimension, quick tell check with frequency data |
| `references/slop-tells.md` | ~45 AI-design tells with code signals and fixes |
| `references/craft-checklist.md` | Interaction, form, typography, accessibility and perf rules |
| `references/design-foundations.md` | How to build the type, color, spacing and motion systems |

The skill triggers on its own whenever an agent builds, styles, or audits web UI.

## Design direction of this project

Product surface (sports dashboard). The visual language is intentionally the **Ultra Analytics Engine v3.0** dark telemetry theme documented in `ultra_analytics_engine_v3_0_design_system.md`. Dark is *earned* here (a scoreboard/analytics surface, often viewed live), which is why the skill's "permanent dark mode" tell is a justified choice rather than an unexamined default.

## Audit pass (first de-slop)

Tell density before the pass was **high (5+)** across the shared stylesheet:

| Tell | Before | After |
| :--- | ---: | ---: |
| `transition: all` (motion without a decision) | 43 | **0** |
| `prefers-reduced-motion` support | 0 in shared styles | added globally |

Second pass (navbar/sidebar feedback):

| Tell | Before | After |
| :--- | ---: | ---: |
| Oversized/tinted shadows (40px drawer, 34px dropdown, 30px card) | 3 | **0** (tokenised) |
| Saturated neon glow on the sidebar active indicator | 1 | **0** |
| Neutral elevation scale in tokens (`--v3-shadow-*`) | absent | added |

### Changes applied

1. **`transition: all` → explicit property lists.** Every occurrence across `resources/views/**` now lists the properties that actually change (background-color, border-color, color, opacity, transform, box-shadow), each with its own timing. This is the skill's "decide which property matters" rule, applied at the root rather than per instance.
2. **Reduced-motion support.** Added a global `@media (prefers-reduced-motion: reduce)` block to `resources/views/home/partials/styles.blade.php` that neutralises animations and transitions for users who ask for it.

### Kept on purpose (justified by the direction)

- Neon-green glow on the hero/v3 titles (`text-shadow`) and one accent `box-shadow` — these are the documented v3.0 accent, aimed at rank/value signals, not decoration.
- Dark surfaces with `--v3-*` tokens — context-earned.
- Pulsing telemetry beacon — encodes a real "live" signal on the analytics screen.

## Rules going forward

- Do not add `transition: all`. Name the properties.
- One accent (neon green), one voice. Decoration must carry information.
- Keep the token layer (`--v3-*` in `styles.blade.php`) as the single source of truth; fix at the variable, not per instance.
- Before delivering new UI, run the quick tell check in the skill and subtract decoration until everything left has to be there.
- Use the `--v3-shadow-*` tokens for elevation. Shadows are neutral, tight, and smaller than the element casting them; a coloured glow is not depth.
