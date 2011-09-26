/**************************************************
 * Crypt functions                                *
 **************************************************/

undead.crypt = {};

undead.crypt.hash = function (m) {
   undead.util.require("undead/sha256");
   return Sha256.hash(m);
};
