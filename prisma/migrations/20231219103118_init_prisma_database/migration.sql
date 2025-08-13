-- CreateTable
CREATE TABLE `tbprisma_tablename` (
    `clId` INTEGER NOT NULL AUTO_INCREMENT,
    `clName` INTEGER NOT NULL,
    `clParent` INTEGER NULL,

    UNIQUE INDEX `clName`(`clName`),
    INDEX `clParent_FK_index`(`clParent`),
    PRIMARY KEY (`clId`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- AddForeignKey
ALTER TABLE `tbprisma_tablename` ADD CONSTRAINT `tbprisma_tablename_ibfk_1` FOREIGN KEY (`clParent`) REFERENCES `tbprisma_tablename`(`clId`) ON DELETE RESTRICT ON UPDATE RESTRICT;
