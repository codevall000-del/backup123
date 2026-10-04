import QRCode from 'qrcode'

export const useQrCode = () => {
  /**
   * Generate PNG Data URL for a given text or QR payload
   */
  const generateDataUrl = async (text: string, options: any = {}): Promise<string> => {
    try {
      if (!text) return ''
      return await QRCode.toDataURL(text, {
        width: options.width || 280,
        margin: options.margin || 2,
        color: {
          dark: options.darkColor || '#0f172a',
          light: options.lightColor || '#ffffff',
        },
        errorCorrectionLevel: 'H'
      })
    } catch (err) {
      console.error('Error generating QR code DataURL:', err)
      return ''
    }
  }

  /**
   * Generate inline SVG string for crisp vector rendering and printing
   */
  const generateSvg = async (text: string, options: any = {}): Promise<string> => {
    try {
      if (!text) return ''
      return await QRCode.toString(text, {
        type: 'svg',
        width: options.width || 240,
        margin: options.margin || 1,
        color: {
          dark: options.darkColor || '#0f172a',
          light: options.lightColor || '#ffffff',
        },
        errorCorrectionLevel: 'H'
      })
    } catch (err) {
      console.error('Error generating QR code SVG:', err)
      return ''
    }
  }

  return {
    generateDataUrl,
    generateSvg
  }
}
