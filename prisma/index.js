"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var db_1 = require("./db");
(async function (){await db_1.default.tbprisma_tablename.create({
    data: {
        clName: 'IOS-DOP'
    }
})
const count = await db_1.default.tbprisma_tablename.count();
console.log("create .... ", count);
})();
console.log("counting:  ", db_1.default.tbprisma_tablename.count());