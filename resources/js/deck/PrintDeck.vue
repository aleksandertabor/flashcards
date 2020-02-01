<template>
  <div>
    <v-btn color="success" @click="print">Print this deck</v-btn>
  </div>
</template>

<script>
import * as jsPDF from "jspdf";
export default {
  props: {
    deck: Object
  },
  data() {
    return {};
  },
  methods: {
    print() {
      var doc = new jsPDF();

      var pageWidth = 595;
      var pageHeight = 842;

      var pageMargin = 20;

      pageWidth -= pageMargin * 2;
      pageHeight -= pageMargin * 2;

      var cellPadding = 10;
      var cellWidth = 180;
      var cellHeight = 70;
      var lineHeight = 20;

      var startX = pageMargin;
      var startY = pageMargin;

      doc.setFontSize(12);

      function createCard(item) {
        console.log(item);
        //cell projection
        var requiredWidth = startX + cellWidth + cellPadding * 2;

        var requiredHeight = startY + cellHeight + cellPadding * 2;

        if (requiredWidth <= pageWidth) {
          textWriter(item, startX + cellPadding, startY + cellPadding);

          startX = requiredWidth;
          //  startY += cellHeight + cellPadding;
        } else {
          if (requiredHeight > pageHeight) {
            doc.addPage();
            startY = pageMargin;
          } else {
            startY = requiredHeight;
          }

          startX = pageMargin;

          textWriter(item, startX + cellPadding, startY + cellPadding);

          startX = startX + cellWidth + cellPadding * 2;
        }
      }

      function textWriter(item, xAxis, yAxis) {
        if (item.question) {
          doc.text(item.question, xAxis, yAxis);
        }
        if (item.answer) {
          doc.text(item.answer, xAxis, yAxis + lineHeight);
        }
        if (item.example_question) {
          doc.text(item.example_question, xAxis, yAxis + lineHeight * 2);
        }
      }

      for (var i = 0; i < this.deck.cards.length; i++) {
        createCard(this.deck.cards[i]);
      }
      doc.save("deck.pdf");
    }
  }
};
</script>

<style scoped>
</style>
