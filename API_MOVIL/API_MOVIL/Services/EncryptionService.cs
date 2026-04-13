using System.Security.Cryptography;

namespace API_MOVIL.Services
{
    public class EncryptionService
    {
        // funcion para tomar un archivo normal y encriptarlo devolviendo
        //-el archivo encriptado
        //-la clave que se ha usado para encriptarlo
        public (byte[] EncryptedFile, string GeneratedKey) Encrypt(byte[] originalFile)
        {
            // creamos el metodo de encriptacion
            using Aes aes = Aes.Create();
            aes.KeySize = 256;
            aes.GenerateKey();
            aes.GenerateIV(); // vector de inicialización

            using MemoryStream msResult = new MemoryStream();

            msResult.Write(aes.IV, 0, aes.IV.Length); // guardar el vector al principio (se necesita para desencriptar)

            using (CryptoStream cryptoStream = new CryptoStream(msResult, aes.CreateEncryptor(), CryptoStreamMode.Write))
            {
                cryptoStream.Write(originalFile, 0, originalFile.Length);
                cryptoStream.FlushFinalBlock();
            }

            // convertir la clave en texto para meter en bd
            string keyInText = Convert.ToBase64String(aes.Key);

            return (msResult.ToArray(), keyInText);


        }

        // funcion para desencriptar el archivo
        public byte[] Decrypt(byte[] encryptedFile, string keyInText)
        {
            using Aes aes = Aes.Create();
            aes.Key = Convert.FromBase64String(keyInText);

            // sacar el vector de inicializacion que se guardo al principio
            byte[] iv = new byte[aes.BlockSize / 8];
            Array.Copy(encryptedFile,0,iv,0,iv.Length);
            aes.IV = iv;

            using MemoryStream msResult = new MemoryStream();
            using (CryptoStream cryptoStream = new CryptoStream(new MemoryStream(encryptedFile, iv.Length, encryptedFile.Length
                - iv.Length), aes.CreateDecryptor(), CryptoStreamMode.Read))
            {
                cryptoStream.CopyTo(msResult);
            }

            return msResult.ToArray();
        }
    }
}
