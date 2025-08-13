import prisma  from './db'
import { Prisma } from '@prisma/client';
(async function(){
 
 
   const g  = await prisma.tbprisma_authors.findMany({
        // select:{ 
        //     clFirstName: true
        
        //     // _count: {
        //     //     select:{
        //     //         other_tbprisma_tablename:true
        //     //     }
        //     // }
        // }, 
        orderBy: {
            clId:'asc'
        },
   });

    // insert data 
    try{

        await prisma.tbprisma_tablename.create({
            data:{
                clName:'IOS_jump'
            }
        });
    } catch{

    }

    await prisma.tbprisma_authors.createMany({
        data:[
            { clFirstName:'Charles', clLastName:'BONDJE DOUE', clParent: 1 }
        ]
    });

    // const count = await prisma.tbprisma_tablename.count();
    return g;
})().then(function(count){
    console.log('Counting update ', JSON.stringify(count));
});

console.log("prisma check"); // counting:  ", prisma.tbprisma_tablename.count());
