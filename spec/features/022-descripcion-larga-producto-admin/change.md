# Change: Descripción larga con texto enriquecido en admin de productos

## Problema / Usuario
El admin de productos solo tiene un campo de descripción plain text (textarea
simple). El vendedor necesita crear descripciones más atractivas con títulos,
negritas, listas, imágenes y formato variado para mejorar la presentación del
catálogo.

## Descripción funcional
- Campo "Descripción Larga" con editor de texto enriquecido (Quill.js) en el
  modal de crear/editar producto del admin.
- Contenido permitido: títulos (h1-h6), negritas, cursivas, listas,
  enlaces, imágenes incrustadas, código, citas, alineación de texto.
- El HTML generado se guarda en un campo `long_description` (text, nullable).
- En el catálogo público, la descripción larga se muestra en la página de
  detalle del producto, debajo de la descripción corta existente.
- La descripción corta existente (`description`) se mantiene.

## Criterios de aceptación
- [ ] Editor Quill.js funcional en el modal de crear/editar producto.
- [ ] Campo `long_description` (text, nullable) en la tabla `products`.
- [ ] El HTML generado se persiste correctamente al guardar.
- [ ] En el detalle público se muestra `long_description` renderizado.
- [ ] La descripción corta sigue funcionando igual.
- [ ] Test Pest para persistencia de `long_description`.