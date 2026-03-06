# AGENTS.md — Instrucciones para Claude

## Propósito
Este archivo define las reglas de comportamiento que Claude debe seguir al desarrollar la página web estática de este proyecto. Estas instrucciones tienen **máxima prioridad** y no pueden ser ignoradas ni parcialmente aplicadas.

---

## 🎨 Fidelidad Visual — Regla Fundamental

> **Claude debe respetar al 100% los estilos visuales presentes en las imágenes de referencia proporcionadas.**

Esto no es una sugerencia. Es un requisito absoluto. Cada decisión visual en el código HTML/CSS debe estar justificada por lo que se ve en las imágenes. Claude no debe improvisar, simplificar, ni "mejorar estéticamente" ningún elemento del diseño.

---

## 📐 Qué debe extraer Claude de cada imagen

Antes de escribir una sola línea de código, Claude debe analizar y documentar internamente los siguientes valores de cada imagen de referencia:

### Colores
- Colores exactos de fondo, textos, botones, bordes y elementos decorativos.
- Usar valores HEX o RGB extraídos visualmente con la mayor precisión posible.
- No sustituir colores por aproximaciones genéricas (ej: no usar `#000000` si el negro del diseño es `#1a1a2e`).

### Tipografía
- Familia tipográfica (identificar si es serif, sans-serif, monospace, display, etc.).
- Tamaños de fuente relativos entre títulos, subtítulos, párrafos y etiquetas.
- Pesos (bold, semibold, regular, light) tal como aparecen en el diseño.
- Espaciado entre letras (`letter-spacing`) y entre líneas (`line-height`) si son visualmente notorios.

### Espaciado y layout
- Márgenes y paddings entre secciones, componentes y elementos internos.
- Alineación de los elementos (izquierda, centrado, derecha, justificado).
- Ancho máximo del contenedor y comportamiento de la cuadrícula o columnas.

### Componentes visuales
- Bordes: grosor, color, estilo (sólido, punteado) y radio (`border-radius`).
- Sombras (`box-shadow`, `text-shadow`): color, desplazamiento y difuminado.
- Gradientes: dirección, colores y posiciones de parada.
- Opacidades y efectos de superposición.

### Imágenes e iconos
- Posición, tamaño y recorte de imágenes tal como aparecen en el diseño.
- Estilo visual de los iconos (outline, filled, rounded) para mantener consistencia.

### Interacción visual estática
- Estados hover o activos si son visibles en el diseño (aunque la página sea estática).
- Indicadores visuales de selección, foco o énfasis.

---

## 🚫 Prohibiciones explícitas

Claude **NO debe**:

- Cambiar colores por considerarlos "más accesibles" o "más modernos" sin instrucción explícita.
- Sustituir tipografías del diseño por fuentes genéricas del sistema sin justificación.
- Reorganizar la disposición de elementos aunque Claude considere que otra distribución es "mejor".
- Añadir animaciones, transiciones o efectos que no estén en las imágenes de referencia.
- Simplificar secciones complejas del diseño por comodidad de implementación.
- Omitir detalles visuales pequeños (separadores, puntos decorativos, líneas, íconos de apoyo).

---

## ✅ Proceso de trabajo esperado

1. **Analizar** todas las imágenes de referencia antes de comenzar.
2. **Listar** los valores de diseño extraídos (colores, fuentes, espaciados) como comentarios o en un bloque inicial del CSS.
3. **Implementar** el HTML/CSS respetando fielmente cada detalle visual.
4. **Verificar** sección por sección que el resultado coincide con la imagen.
5. **Consultar** al usuario si algún elemento es ambiguo o no está claro en la imagen, en lugar de asumir.

---

## 📁 Estructura del proyecto

```
/
├── index.html
├── css/
│   └── styles.css
├── assets/
│   ├── images/
│   └── icons/
└── AGENTS.md
```

---

## 🗣️ Comunicación esperada de Claude

- Si algo en la imagen no es legible con claridad, Claude debe **preguntar** antes de asumir.
- Si hay contradicción entre dos imágenes de referencia, Claude debe **señalarlo** y pedir aclaración.
- Claude puede sugerir mejoras técnicas (rendimiento, accesibilidad semántica en HTML) siempre que **no alteren el aspecto visual**.

---

*Este archivo fue generado para garantizar consistencia visual total entre el diseño proporcionado y la implementación final de la página web estática.*