const texts = require('../heroTexts');

describe('Array de frases para typing text', () => {
  test('es un array', () => {
    expect(Array.isArray(texts)).toBe(true);
  });

  test('tiene al menos 5 frases', () => {
    expect(texts.length).toBeGreaterThanOrEqual(5);
  });

  test('contiene frases esperadas', () => {
    expect(texts).toContain('Tu éxito, nuestra prioridad.');
    expect(texts).toContain('Impulsá tus ventas.');
  });

  test('todas las frases son strings y no están vacías', () => {
    texts.forEach(text => {
      expect(typeof text).toBe('string');
      expect(text.length).toBeGreaterThan(0);
    });
  });
});
