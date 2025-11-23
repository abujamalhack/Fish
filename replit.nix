{ pkgs }:
pkgs.mkShell {
  buildInputs = with pkgs; [
    php
    phpPackages.composer
  ];
}
