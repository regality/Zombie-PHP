/**************************************************
 * Crypt functions                                *
 **************************************************/

zs.crypt = {};

zs.crypt.hash = function (m) {
   zs.util.require("zombiescript/sha256");
   return Sha256.hash(m);
};
