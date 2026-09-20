# Layout Standards — Agentic AI Web App

This document defines exact layout rules: spacing, grid, positioning, and component placement. Use it together with `design-guidelines-agentic-ai-en.md` (visual style) — this file is about *where things go and how far apart they are*. Follow it precisely so every screen feels like the same product.

---

## 1. Base Unit & Spacing Scale

Base unit: **4px**. Every margin, padding, and gap must be a multiple of this scale — never an arbitrary number like 13px or 22px.

| Token | Value | Typical use |
|---|---|---|
| `space-1` | 4px | Icon-to-label gap, tight inline spacing |
| `space-2` | 8px | Gap between related small elements (icon + text, badge padding) |
| `space-3` | 12px | Input padding (vertical), gap between form fields in a row |
| `space-4` | 16px | Standard card padding, gap between stacked form fields |
| `space-5` | 24px | Gap between distinct component groups |
| `space-6` | 32px | Section padding (mobile), gap between major blocks |
| `space-8` | 48px | Section padding (desktop) |
| `space-10` | 64px | Page-level vertical rhythm between major sections |
| `space-12` | 96px | Large hero/empty-state breathing room |

Rule: pick spacing based on **relationship**, not decoration. Closely related elements get small spacing; unrelated groups get larger spacing. Never use the same gap for "next to related item" and "next to a whole different section."

---

## 2. Grid & Containers

- **Max content width:** 1200–1280px for wide dashboards; 720–768px for reading-focused content (docs, chat transcript column, settings forms).
- **Side padding (page gutters):**
  - Mobile (< 640px): 16px
  - Tablet (640–1024px): 24px
  - Desktop (> 1024px): 32px (or center content within max-width, whichever is larger)
- **Column grid:** 12-column grid for desktop layouts, gutter 24px. Sidebar + main content is typically a 2-column split, not a 12-col grid (see §4).
- **Vertical rhythm:** major sections on a page are separated by `space-8`–`space-10`; elements within one section use `space-4`–`space-5`.

---

## 3. Breakpoints

| Name | Width | Notes |
|---|---|---|
| `sm` (mobile) | < 640px | Single column, sidebar becomes a drawer/overlay |
| `md` (tablet) | 640–1024px | Sidebar can collapse to icons-only or overlay |
| `lg` (desktop) | 1024–1440px | Full sidebar + main content visible |
| `xl` (wide desktop) | > 1440px | Content stays at max-width, extra space becomes margin — never stretch cards/text full-width |

Rule: text content and form widths must NOT stretch to fill ultra-wide screens. Cap line-length containers at ~720px regardless of viewport size.

---

## 4. App Shell Layout (Agentic AI Pattern)

Standard 3-zone shell:

```
┌───────────┬──────────────────────────────┬───────────────┐
│           │  Top bar (optional, 56–64px) │               │
│  Sidebar  ├──────────────────────────────┤  Activity /   │
│  240–280px│                              │  status panel │
│  fixed    │      Main work area          │  (optional)   │
│           │      (chat / content)        │  320–360px    │
│           │                              │               │
└───────────┴──────────────────────────────┴───────────────┘
```

- **Sidebar width:** 240–280px fixed. Collapsible to 56–64px (icon-only) on demand, never auto-hidden without user control on desktop.
- **Top bar height:** 56–64px if present. Contains only: current context title/breadcrumb, and 1–3 global actions (never more than 3 icons besides overflow menu).
- **Main work area:** flexible width, `max-width` applied to its *inner content* (chat bubbles, forms) even if the container is full width — center that inner content or left-align with a fixed max-width, don't stretch text edge-to-edge.
- **Activity/status panel (tool calls, agent steps):** 320–360px fixed, collapsible. On screens < 1024px, this becomes a bottom drawer or modal instead of a permanent side column.
- On mobile: sidebar and activity panel both become off-canvas (slide-in overlays), main work area takes full width.

---

## 5. Button Placement & Sizing

**Sizing**
| Size | Height | Horizontal padding | Use |
|---|---|---|---|
| Small | 32px | 12px | Inline table/list actions, compact toolbars |
| Medium (default) | 40px | 16px | Standard forms, dialogs |
| Large | 48px | 24px | Primary landing/marketing CTAs only |

- Minimum tappable area: 40x40px on touch devices, even if the visible button is smaller (pad with invisible hit area).
- Icon-only buttons: square, same height as text buttons of that size (e.g. 40x40px for medium), icon centered.

**Placement**
- **Forms/dialogs:** primary action bottom-right, secondary/cancel to its immediate left, gap `space-3` (12px) between them. Never put primary action on the left of secondary.
  ```
  [ Cancel ]  [ Save changes ]
  ```
- **Destructive actions** (delete, remove): visually separated from the primary action group — either far-left of the same row, or in a distinct danger zone/section — never directly adjacent to a primary confirm button.
- **Page-level primary action** (e.g. "New task," "New conversation"): top-right of the content area, or top of sidebar list if it's the dominant action for that section.
- **Toolbar actions:** left-aligned = navigation/filtering controls; right-aligned = actions/settings. Keep this split consistent across all toolbars in the app.
- **Only one primary (filled/accent) button visible per view.** All other actions are secondary (outline/ghost) or tertiary (text-only).
- Button groups (segmented controls, tabs-as-buttons): equal width unless label length forces otherwise, no gap between segments, shared border.

---

## 6. Forms

- Label position: above the input, left-aligned, `space-1` (4px) gap between label and input.
- Field vertical gap (stacked): `space-4` (16px) between fields; `space-2` (8px) between an input and its helper/error text below it.
- Input height: 40px default (matches medium button height so they align in a row).
- Input horizontal padding: 12px.
- Required-field marker: consistent single method across the whole app (e.g. asterisk after label) — don't mix "(required)" text and asterisks in different forms.
- Error message: appears directly below the field, in `--danger` color, `space-2` gap, never as a tooltip-only or toast-only (toast is for submit-level errors, inline text is for field-level errors).
- Form action row (submit/cancel) always sits at the bottom, separated from the last field by `space-5`–`space-6`.

---

## 7. Cards & Lists

- Card padding: 16px (compact/list-item cards) to 24px (standalone content cards).
- Gap between cards in a grid: `space-4` (16px) mobile, `space-5` (24px) desktop.
- List rows (table-like lists, e.g. conversation list, task list): 48–56px row height minimum, 12–16px horizontal padding, single hairline border between rows (not full card-per-row shadow treatment).
- Card internal structure order (top to bottom or left to right): primary identifier (title/name) → metadata/status → actions. Actions are always last (right-aligned in a row layout, bottom in a stacked layout).
- Hover/selected state on list rows: background surface shift only, no shadow pop or scale transform.

---

## 8. Chat / Conversation Layout (Agentic-Specific)

- Message column max-width: 680–760px, centered or left-aligned within the main work area — never full viewport width even on ultra-wide screens.
- Vertical gap between messages from different speakers: `space-4` (16px). Between consecutive messages from the same speaker (if grouped): `space-2` (8px).
- Avatar/role indicator: fixed width column (32–40px), aligned to the top of the first line of text, not vertically centered against multi-line messages.
- Tool-call blocks inside a message: inset slightly from the message text edge (indent `space-3`–`space-4`), with their own internal padding of `space-3` (12px), clearly bordered so they're distinguishable from prose but not heavier than the message itself.
- Input/composer bar: fixed to the bottom of the main work area, min-height 48px, padding 12–16px, max-width matches the message column above it.
- Timestamps/status ("sending," "done," "error"): small text (`text-secondary`), placed either inline after the last message action or in a fixed small area below the message — pick one pattern and use it everywhere.

---

## 9. Modals & Overlays

- Modal max-width: 480px (confirmation/simple form) to 640px (complex form). Never full-screen on desktop unless it's a dedicated full-page flow.
- Modal padding: 24px on all sides.
- Header (title + close button) separated from body by `space-4`, body separated from footer/actions by `space-5`.
- Close button (X): always top-right, 40x40px hit area.
- Footer actions: same rule as §5 (primary bottom/right-most, cancel to its left).
- Overlay backdrop: consistent opacity across the whole app (don't vary dimming per modal).

---

## 10. Alignment Rules (Summary)

- **Left-align:** body text, form labels, table/list content, navigation items, work/dashboard sections.
- **Center-align:** empty states, onboarding screens, standalone marketing/landing sections, modal titles when the modal is a simple confirmation.
- **Right-align:** numeric table columns, primary action buttons in a row, timestamps in a list row.
- Never mix center-aligned body paragraphs with left-aligned everything else on the same page — pick the alignment logic per page type and keep it.

---

## 11. Z-Index Layers

Define a fixed scale and never use arbitrary z-index numbers:

| Layer | z-index |
|---|---|
| Base content | 0 |
| Sticky headers/toolbars | 10 |
| Dropdowns/popovers | 20 |
| Off-canvas drawers (mobile sidebar) | 30 |
| Modal backdrop | 40 |
| Modal content | 41 |
| Toast/notification | 50 |
| Tooltip | 60 |

---

## 12. Responsive Behavior Checklist

- [ ] Sidebar collapses to off-canvas drawer below 1024px, doesn't just shrink and clip content.
- [ ] Activity/status panel becomes a bottom sheet/modal on mobile instead of a squeezed column.
- [ ] Buttons keep 40x40px minimum tap target on touch, even if visually smaller on desktop.
- [ ] Text/content containers never exceed their max-width, regardless of screen size.
- [ ] Table-like lists switch to stacked card layout below `sm` breakpoint if columns can't fit.
- [ ] No horizontal scroll on the page body itself (only intentional horizontal-scroll components, e.g. a code block or wide table, may scroll).

---

## 13. Pre-Ship Layout Checklist

- [ ] All spacing values come from the defined scale (§1) — no arbitrary pixel values.
- [ ] Only one primary button visible per screen/view.
- [ ] Primary action is bottom-right (dialogs) or top-right (page-level), consistently.
- [ ] Destructive actions are visually separated from primary/confirm actions.
- [ ] Message/content columns are width-capped even on wide screens.
- [ ] Z-index values match the defined layer scale.
- [ ] Same alignment logic used consistently within each page type.
- [ ] Verified at all breakpoints (mobile, tablet, desktop, wide desktop).

---

*Use this file alongside the visual design guidelines file. Together they define both "how it should look" and "exactly where everything goes."*
