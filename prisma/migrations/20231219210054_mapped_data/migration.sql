/*
  Warnings:

  - You are about to alter the column `clBaseType` on the `tbprisma_authors` table. The data in that column could be lost. The data in that column will be cast from `Enum(EnumId(0))` to `Enum(EnumId(0))`.

*/
-- AlterTable
ALTER TABLE `tbprisma_authors` MODIFY `clBaseType` ENUM('1', '2') NOT NULL DEFAULT '1';
